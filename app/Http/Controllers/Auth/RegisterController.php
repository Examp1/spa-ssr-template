<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Profile\RegConfirmEmail;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use App\Service\TelegramBot;
use App\Models\UserVerified;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    private $telegramBot;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TelegramBot $telegramBot)
    {
        $this->redirectTo  = url('profile');
        $this->telegramBot = $telegramBot;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'email.required' => "*Електронна пошта повинна містити @",
            'email.email'    => "*Електронна пошта повинна містити @",
            'email.unique'   => "*Користувач з такою поштою вже існує",
        ];

        return Validator::make($data, [
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\Admin
     */
    protected function create(array $data)
    {
        /* @var $user Admin */
        $user = Admin::create([
            'name'           => '',
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'status'         => Admin::STATUS_NOT_ACTIVE
        ]);

        $token = Str::random(32);

        UserVerified::create([
            'field' => $user->email,
            'code'  => $token,
            'type'  => UserVerified::TYPE_EMAIL
        ]);

        // отправить email  новым паролем
        $objData       = new \stdClass();
        $objData->link = url('/user-email-confirm/token/' . $token);

        Mail::to($user->email)->send(new RegConfirmEmail($objData));

        Auth::attempt(['email' => $user->email, 'password' => $data['password'], 'status' => Admin::STATUS_NOT_ACTIVE]);

        //        $message = "Новая регистрация" . PHP_EOL;
        //        $message .= "Имя - " . $data['name'] . PHP_EOL;
        //        $message .= "Компания - " . $data['company'] . PHP_EOL;
        //        $message .= "E-mail - " . $data['email'] . PHP_EOL;
        //
        //
        //        if ($user) {
        //            $this->telegramBot->sendMessage($message);
        //        }

        return $user;
    }
}
