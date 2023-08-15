<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Mail\Profile\RegConfirmEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\PasswordResets;
use App\Models\User;
use App\Models\UserVerified;
use App\Service\SendMailGunTemplate;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResetController extends Controller
{
    use ResponseTrait;

    /**
     * @var SendMailGunTemplate
     */
    private $mailer;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(SendMailGunTemplate $sendMailGunTemplate)
    {
        $this->middleware('auth:api', ['except' => ['reset', 'resetPostEmail', 'passwordChange']]);
        $this->mailer = $sendMailGunTemplate;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['email'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['email']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::query()->where('email', $decodedJson['email'])->first();

        if (!$user) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $token = Str::random(64);

        PasswordResets::query()
            ->where('type', PasswordResets::TYPE_EMAIL)
            ->where('field', $request->get('email'))
            ->delete();

        PasswordResets::insert([
            'field'      => $decodedJson['email'],
            'type'       => PasswordResets::TYPE_EMAIL,
            'code'       => $token,
            'created_at' => Carbon::now()
        ]);

        $link = url('/password-reset/token/' . $token);
        $template = SendMailGunTemplate::TEMPLATE_RESET_PASSWORD;

        if ($lang != config('translatable.locale')) {
            $template .= '_' . $lang;
        }

        if(config('api_account.debug')){
            Log::info('api_debug',[
                'method' => 'reset',
                'data' => [
                    'link'  => $link,
                    'email' => $user->email
                ]
            ]);
        } else {
            $this->mailer->sendMail(
                config('mail.mailers.mailgun.from_address'),
                $user->email,
                __('Reset password'),
                $template,
                [
                    'name' => $user->name,
                    'link' => $link
                ]
            );
        }

        return $this->successResponse([
            'message' => 'Email sent for reset',
        ]);
    }

    public function resetPostEmail(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['token'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['token']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* @var $model PasswordResets */
        $model = PasswordResets::query()
            ->where('type', PasswordResets::TYPE_EMAIL)
            ->where('code', $decodedJson['token'])
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNAUTHORIZED
            );

        $token = base64_encode('*code*' . $decodedJson['token'] . '*code*' . $model->field . '*code*' . PasswordResets::TYPE_EMAIL);

        return $this->successResponse([
            'message' => 'Password change',
            'token'   => $token,
        ]);
    }

    public function passwordChange(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['token'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['token']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['password'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['password']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $fields = explode('*code*', base64_decode($decodedJson['token']));

        if(count($fields) < 3){
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_TOKEN_NOT_VALID),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $data = [
            'code'  => $fields[1],
            'field' => $fields[2],
            'type'  => $fields[3]
        ];

        /* @var $model PasswordResets */
        $model = PasswordResets::query()
            ->where('type', $data['type'])
            ->where('code', $data['code'])
            ->where('field', $data['field'])
            ->first();

        if (!$model)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNAUTHORIZED
            );

        $typeField = $data['type'] == PasswordResets::TYPE_EMAIL ? 'email' : 'phone';

        $user = User::query()
            ->where($typeField, $data['field'])
            ->first();

        if (!$user)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNAUTHORIZED
            );

        $user->password = Hash::make($decodedJson['password']);
        $user->save();

        PasswordResets::query()
            ->where('type', $data['type'])
            ->where('code', $data['code'])
            ->where('field', $data['field'])
            ->delete();

        if (!$token = auth('api')->attempt([
            'email'    => $user->email,
            'password' => $decodedJson['password'],
            'status'   => User::STATUS_REGISTER
        ])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_INVALID_LOGIN_PASSWORD_PAIR),
                Response::HTTP_UNAUTHORIZED
            );
        }

        /* Скинуть лимит попыток входа */
        $ip = $request->getClientIp();
        $key = 'max_login_attempts_' . $ip;
        Cache::put($key,'0',config('api_account.login.lockout_time'));

        return $this->successResponse([
            'message'      => 'Password successfully changed',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL(),
            'user'         => auth('api')->user()
        ]);
    }
}
