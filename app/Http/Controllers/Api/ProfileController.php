<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Mail\Profile\RegConfirmEmail;
use App\Models\User;
use App\Models\UserVerified;
use App\Service\SendMailGunTemplate;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * @var SendMailGunTemplate
     */
    private $mailer;

    public function __construct(SendMailGunTemplate $sendMailGunTemplate)
    {
        $this->mailer = $sendMailGunTemplate;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $user User */
        $user = auth('api')->user();

        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (isset($decodedJson['name']) && $decodedJson['name']) {
            $user->name = $decodedJson['name'];
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        if (isset($decodedJson['phone']) && $decodedJson['phone']) {
            if (!config('api_account.registration.phone_confirm')) {
                // если не нужно подтверждение по телефону
                $user->phone = $decodedJson['phone'];
            } else {
                return $this->returnPhoneConfirm($user, $decodedJson['phone']);
            }
        }

        if (isset($decodedJson['email']) && $decodedJson['email']) {
            if (!config('api_account.registration.email_confirm')) {
                // если не нужно подтверждение по email
                $user->email = $decodedJson['email'];
            } else {
                if(User::query()->where('email',$decodedJson['email'])->exists()){
                    return $this->errorResponse(
                        ErrorManager::buildError(VALIDATION_EMAIL_ALREADY_EXISTS)
                    );
                }

                return $this->returnEmailConfirm($user, $decodedJson['email'], $lang);
            }
        }

        if (isset($decodedJson['oldPassword']) && isset($decodedJson['newPassword']) && $decodedJson['oldPassword'] && $decodedJson['newPassword']) {
            if (Hash::check($decodedJson['oldPassword'], $user->password)) {
                // если не нужно подтверждение
                $user->password = Hash::make($decodedJson['newPassword']);
            } else {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }

        $user->save();

        return $this->successResponse(['user' => $user]);
    }

    public function phoneConfirm(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $user User */
        $user = auth('api')->user();

        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!isset($decodedJson['code'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['code']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['new_phone'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['new_phone']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $verified UserVerified */
        $verified = UserVerified::query()
            ->where('type', UserVerified::TYPE_PHONE)
            ->where('code', $decodedJson['code'])
            ->first();

        if (!$verified)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $user->phone_verified_at = Carbon::now();
        $user->phone             = $decodedJson['new_phone'];
        $user->save();
        $verified->delete();

        return $this->successResponse([
            'message' => 'Phone changed successfully',
            'user'    => $user
        ]);
    }

    public function emailConfirm(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $user User */
        $user = auth('api')->user();

        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!isset($decodedJson['token'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['token']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['new_email'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['new_email']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $verified UserVerified */
        $verified = UserVerified::query()
            ->where('type', UserVerified::TYPE_EMAIL)
            ->where('code', $decodedJson['token'])
            ->first();

        if (!$verified)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $user->email_verified_at = Carbon::now();
        $user->email = $decodedJson['new_email'];
        $user->save();
        $verified->delete();

        return $this->successResponse([
            'message' => 'E-mail successfully changed',
            'user'    => $user
        ]);
    }

    private function returnPhoneConfirm(User $user, $phone)
    {
        $code = rand(100000, 999999);

        UserVerified::create([
            'field' => $user->phone,
            'code'  => $code,
            'type'  => UserVerified::TYPE_PHONE
        ]);

        if(config('api_account.debug')){
            Log::info('api_debug',[
                'method' => 'returnPhoneConfirm',
                'data' => [
                    'field' => $user->phone,
                    'code' => $code,
                    'type' => UserVerified::TYPE_PHONE
                ]
            ]);
        } else {
            /* Send sms */
            $sended = TurboSMS::sendMessages($phone, 'Код подтверждения: ' . $code, 'sms');
        }

        return $this->successResponse([
            'message'   => 'Sms code sent for confirmation',
            'new_phone' => $phone
        ], Response::HTTP_CREATED);
    }

    private function returnEmailConfirm(User $user, $email, $lang = 'uk')
    {
        $token = Str::random(32);

        UserVerified::create([
            'field' => $user->email,
            'code'  => $token,
            'type'  => UserVerified::TYPE_EMAIL
        ]);

        $link = url('/profile/user-email-confirm/token/' . $token);

        $template = SendMailGunTemplate::TEMPLATE_REGISTRATION_CONFIRM_EMAIL;

        if ($lang != config('translatable.locale')) {
            $template .= '_' . $lang;
        }

        if(config('api_account.debug')){
            Log::info('api_debug',[
                'method' => 'returnEmailConfirm',
                'data' => [
                    'link'  => $link,
                    'email' => $email
                ]
            ]);
        } else {
            $this->mailer->sendMail(
                config('mail.mailers.mailgun.from_address'),
                $email,
                __('Email confirmation'),
                $template,
                [
                    'name' => $user->name,
                    'link' => $link
                ]
            );
        }

        return $this->successResponse([
            'message'   => 'Email sent for confirmation',
            'new_email' => $email
        ], Response::HTTP_CREATED);
    }
}
