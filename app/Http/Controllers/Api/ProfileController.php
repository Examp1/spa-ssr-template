<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Mail\RegConfirmEmail;
use App\Models\User;
use App\Models\UserVerified;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {

            if (!$decodedJson = $request->json()->all()) {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            if (isset($decodedJson['lang'])) {
                $lang = $decodedJson['lang'];
            } else {
                $lang = config('translatable.locale');
            }

            app()->setLocale($lang);

            $data['user'] = [
                'name'  => $user->name,
                'phone' => $user->phone,
                'email' => $user->email
            ];


            $data['orders'] = [];

            foreach ($user->orders as $order) {
                $data['orders'][] = app(CartController::class)->formatOrder($order);
            }

            $data['wishlist'] = [
                'share'    => $user->wishlist_share ?: '',
                'products' => $user->wishlists()->with([
                    'product' => function ($query) {
                        $query->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                            ->where('product_translations.lang', app()->getLocale());
                        $query->with([
                            'prices' => function ($query) {
                                $query->active();
                            }
                        ]);
                        $query->select([
                            'products.*',
                            'product_translations.name',
                        ]);
                    },
                ])->get()->toArray(),
            ];
            return $this->successResponse($data);
        }

        return $this->errorResponse(
            ErrorManager::buildError(VALIDATION_UNAUTHORIZED, ['user']),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
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

        $fields = [
            'name'     => 'required|min:2',
            'lastname' => 'required|min:2',
            // 'surname' => 'required|min:2',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:255',
            'birthday' => 'nullable|date|before:today',
        ];

        $validateFields = [];



        foreach ($fields as $field_name => $field_rule) {
            if (isset($decodedJson[$field_name]) && !empty($decodedJson[$field_name])) {
                $validateFields[$field_name] = $field_rule;
            }
        }

        $validator = Validator::make($decodedJson, $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, $validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $validatedData = $validator->validated();

        if (!empty($validatedData)) {
            $user->update($validatedData);
        }

        if (isset($validatedData['phone']) && $validatedData['phone']) {
            if (!config('api_account.registration.phone_confirm')) {
                // если не нужно подтверждение по телефону
                $user->phone = $validatedData['phone'];
            } else {
                return $this->returnPhoneConfirm($user, $validatedData['phone']);
            }
        }

        if (isset($validatedData['email']) && $validatedData['email']) {
            $user_exist = User::query()->where(function ($query) use ($validatedData, $user) {
                $query->where('email', $validatedData['email']);
                $query->where('id', '<>', $user->id);
            })->exists();

            if ($user_exist) {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_EMAIL_ALREADY_EXISTS)
                );
            }

            $user->email = $validatedData['email'];
            $user->email_verified_at = null;
            $user->save();

            if (!config('api_account.registration.email_confirm')) {
                // если не нужно подтверждение по email
                // $user->email = $decodedJson['email'];
            } else {
                return $this->returnEmailConfirm($user, $validatedData['email'], $lang);
            }
        }

        if (isset($decodedJson['oldPassword']) && isset($decodedJson['newPassword']) && $decodedJson['oldPassword'] && $decodedJson['newPassword']) {
            if (Hash::check($decodedJson['oldPassword'], $user->password)) {
                // если не нужно подтверждение
                $user->password = Hash::make($decodedJson['newPassword']);
                $user->save();
            } else {
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_UNAUTHORIZED),
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }

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

        // if (!isset($decodedJson['new_email'])) {
        //     return $this->errorResponse(
        //         ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['new_email']),
        //         Response::HTTP_UNPROCESSABLE_ENTITY
        //     );
        // }

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
        // $user->email = $decodedJson['new_email'];
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

        if (config('api_account.debug')) {
            Log::info('api_debug', [
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

        if (config('api_account.debug')) {
            Log::info('api_debug', [
                'method' => 'returnEmailConfirm',
                'data' => [
                    'link'  => $link,
                    'email' => $email
                ]
            ]);
        } else {
            $objData       = new \stdClass();
            $objData->name = $user->name;
            $objData->link = $link;

            Mail::to($email)->send(new RegConfirmEmail($objData));
        }

        return $this->successResponse([
            'message'   => 'Email sent for confirmation',
            'new_email' => $email
        ], Response::HTTP_CREATED);
    }
}
