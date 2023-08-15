<?php

namespace App\Http\Controllers\Api;

use App\Core\Error\ErrorManager;
use App\Core\Response\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Models\User;
use App\Models\UserVerified;
use App\Service\SendMailGunTemplate;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
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
        $this->middleware('auth:api', ['except' => ['login', 'register', 'emailConfirm', 'phoneConfirm','redirectToProvider','handleProviderCallback']]);
        $this->mailer = $sendMailGunTemplate;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($request->input('lang')) {
            $lang = $request->input('lang');
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD,$validator->errors()->toArray()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        /* Проверить лимит попыток входа */
        $ip = $request->getClientIp();
        $key = 'max_login_attempts_' . $ip;

        if(Cache::get($key,0) > config('api_account.login.max_login_attempts')){
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_EXCEEDED_LIMIT_OF_LOGIN_ATTEMPTS,
                    ['lockout_time' => config('api_account.login.lockout_time')]
                ),
                Response::HTTP_UNAUTHORIZED
            );
        }
        /*********************************************************/

        // Проверить подтверджена ли почта, если нужно
        if(config('api_account.registration.email_confirm')){
            $user = User::query()->where('email',$request->get('email'))->first();
            if($user && is_null($user->email_verified_at)){
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_ACCOUNT_NOT_VERIFIED_BY_EMAIL),
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }

        if (!$token = auth('api')->attempt(array_merge($validator->validated(),['status' => User::STATUS_REGISTER]))) {
            if(!Cache::has($key)){
                Cache::put($key, '1', config('api_account.login.lockout_time'));
            }

            Cache::increment('max_login_attempts_' . $ip);

            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_INVALID_LOGIN_PASSWORD_PAIR),
                Response::HTTP_UNAUTHORIZED
            );
        }

        Cache::put($key,'0',config('api_account.login.lockout_time'));

        /* Если нужно подтверждение регистрации по e-mail */
        if(config('api_account.registration.email_confirm')){
            $email_verified_at = auth('api')->user()->email_verified_at;

            if(empty($email_verified_at))
            {
                auth('api')->logout();
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_ACCOUNT_NOT_VERIFIED_BY_EMAIL),
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }

        /* Если нужно подтверждение регистрации по телефону */
        if(config('api_account.registration.phone_confirm')){
            $phone_verified_at = auth('api')->user()->phone_verified_at;

            if(empty($phone_verified_at))
            {
                auth('api')->logout();
                return $this->errorResponse(
                    ErrorManager::buildError(VALIDATION_ACCOUNT_NOT_VERIFIED_BY_PHONE),
                    Response::HTTP_UNAUTHORIZED
                );
            }
        }

        return $this->createNewToken($token);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $user_main_filed = 'email';
        $validateFields = [
            'name'     => 'required|string|between:2,100',
            'phone'    => 'string|between:2,100',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];

        if ($request->input('lang')) {
            $lang = $request->input('lang');
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

        $shadow = User::where('status', User::STATUS_NOT_ACTIVE)->where('email', $request->input('email'))->first();

        if ($shadow) {
            $validateFields['email'] = 'required|string|email|max:100';
        }

        /* Если нужно подтверждение по телефону ****************************/
        if(config('api_account.registration.phone_confirm')){
            $validateFields = array_merge($validateFields,['phone' => 'required|string|unique:users']);

            $shadow = User::where('status', User::STATUS_NOT_ACTIVE)->where('phone', $request->input('phone'))->first();

            if ($shadow) {
                $validateFields['email'] = 'required|string|email|max:100';
            }

            $user_main_filed = 'phone';
        }

        $validator = Validator::make($request->all(), $validateFields);

        if ($validator->fails()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD,$validator->errors()->toArray()),
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = User::updateOrCreate(
            [
                $user_main_filed => $request->input($user_main_filed),
            ],
            array_merge(
                $validator->validated(),
                [
                    'status' => (config('api_account.registration.email_confirm') !== true && config('api_account.registration.phone_confirm') !== true)
                        ? User::STATUS_REGISTER
                        : User::STATUS_NOT_ACTIVE,
                    'password' => bcrypt($request->get('password'))
                ]
            )
        );

        /* Если нужно подтверждение по e-mail ****************************/
        if(config('api_account.registration.email_confirm')){
            return $this->returnEmailConfirm($user, $lang);
        }
        /****************************************************************/

        /* Если нужно подтверждение по телефону ****************************/
        if(config('api_account.registration.phone_confirm')){
            return $this->returnPhoneConfirm($user, $lang);
        }
        /****************************************************************/

        return $this->successResponse([
            'message' => 'User successfully registered',
            'user'    => $user
        ],Response::HTTP_CREATED);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->successResponse(['message' => 'User successfully signed out']);
}

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user = auth('api')->user();

        $data = [
            'name'  => $user->name,
            'phone' => $user->phone,
            'email' => $user->email
        ];

        return $this->successResponse($data);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL(),
            'user'         => auth('api')->user()
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

        if (!isset($decodedJson['token'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['token']),
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

        /* @var $user User */
        $user = User::query()->where('email', $verified->field)
            ->where('status', User::STATUS_NOT_ACTIVE)
            ->first();

        if (!$user)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $user->email_verified_at = Carbon::now();
        $user->status = User::STATUS_REGISTER;
        $user->save();
        $verified->delete();

        /* Если нужно подтверждение по телефону ****************************/
        if(config('api_account.registration.phone_confirm') && is_null($user->phone_verified_at)){
            $user->status = User::STATUS_NOT_ACTIVE;
            $user->save();
            return $this->returnPhoneConfirm($user);
        }
        /****************************************************************/

        return $this->successResponse([
            'message' => 'User successfully registered',
            'user'    => $user
        ],Response::HTTP_CREATED);
    }

    public function phoneConfirm(Request $request)
    {
        if (!$decodedJson = $request->json()->all()) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUEST_JSON_EXPECTED),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!isset($decodedJson['code'])) {
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_REQUIRED_FIELD, ['code']),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (isset($decodedJson['lang'])) {
            $lang = $decodedJson['lang'];
        } else {
            $lang = config('translatable.locale');
        }

        app()->setLocale($lang);

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

        /* @var $user User */
        $user = User::query()->where('phone', $verified->field)
            ->where('status', User::STATUS_NOT_ACTIVE)
            ->first();

        if (!$user)
            return $this->errorResponse(
                ErrorManager::buildError(VALIDATION_MODEL_NOT_FOUND),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $user->phone_verified_at = Carbon::now();
        $user->status = User::STATUS_REGISTER;
        $user->save();
        $verified->delete();

        /* Якщо потрібно підтвердження по e-mail ****************************/
        if(config('api_account.registration.email_confirm') && is_null($user->email_verified_at)){
            $user->status = User::STATUS_NOT_ACTIVE;
            $user->save();
            return $this->returnEmailConfirm($user);
        }
        /****************************************************************/

        return $this->successResponse([
            'message' => 'User successfully registered',
            'user'    => $user
        ],Response::HTTP_CREATED);
    }

    private function returnEmailConfirm(User $user, $lang = 'uk')
    {
        $token = Str::random(32);

        UserVerified::create([
            'field' => $user->email,
            'code'  => $token,
            'type'  => UserVerified::TYPE_EMAIL
        ]);


        $link = url('/user-email-confirm/token/' . $token);
        $template = SendMailGunTemplate::TEMPLATE_REGISTRATION_CONFIRM_EMAIL;

        // templates for localization with _{locale} in the end
        if ($lang != config('translatable.locale')) {
            $template .= '_' . $lang;
        }

        // todo send mail
        if(config('api_account.debug')){
            Log::info('api_debug',[
                'method' => 'returnEmailConfirm',
                'data' => [
                    'email' => $user->email,
                    'link'  => $link
                ]
            ]);
        } else {
            $this->mailer->sendMail(
                config('mail.mailers.mailgun.from_address'),
                $user->email,
                __('Email confirmation'),
                $template,
                [
                    'name' => $user->name,
                    'link' => $link
                ]
            );
        }

        return $this->successResponse([
            'message' => 'Email sent for confirmation',
        ],Response::HTTP_CREATED);
    }

    private function returnPhoneConfirm(User $user)
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
            $sended = TurboSMS::sendMessages($user->phone, 'Код подтверждения: ' . $code, 'sms');
        }

        return $this->successResponse([
            'message' => 'Sms code sent for confirmation',
        ],Response::HTTP_CREATED);
    }

    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        Log::info('handleProviderCallback',[$driver]);
        try {
            $user = Socialite::driver($driver)->user();
            Log::info('handleProviderCallbackUser',[$user]);
        } catch (\Exception $e) {

        }

        $existingUser = User::query()->where('email', $user->getEmail())->first();

        if ($existingUser) {
            $token = auth('api')->login($existingUser, true);
        } else {
            $newUser = new User;
            $newUser->provider_name = $driver;
            $newUser->provider_id = $user->getId();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->email_verified_at = now();
            $newUser->status = User::STATUS_REGISTER;
            $newUser->save();

            $token = auth('api')->login($newUser, true);
        }

        return redirect('s-login/'.$token);
    }

    public function sLogin($token){
        $page = new Pages();

        return view('front.home.frontend',[
            'page' => $page,
        ]);
    }
}
