<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Mail\ResetPasswordEmail;
use App\Models\PasswordResets;
use App\Models\User;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MyResetController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.passwords.index', []);
    }

    public function resetPostEmail($token)
    {
        /* @var $model PasswordResets */
        $model = PasswordResets::query()
            ->where('type', PasswordResets::TYPE_EMAIL)
            ->where('code', $token)
            ->first();

        if (!$model)
            abort(404);

        $token = base64_encode('*code*' . $token . '*code*' . $model->field . '*code*' . PasswordResets::TYPE_EMAIL);

        return redirect('/password-change/' . $token);
    }

    public function resetByEmail(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('auth.passwords.email', []);
        } elseif ($request->method() === 'POST') {
            $user = User::query()->where('email', $request->get('email'))->first();

            if (!$user)
                return redirect()->back()->with('error', 'Користувача не знайдено!');

            $token = Str::random(64);

            PasswordResets::query()
                ->where('type', PasswordResets::TYPE_EMAIL)
                ->where('field', $request->get('email'))
                ->delete();

            PasswordResets::insert([
                'field'      => $request->get('email'),
                'type'       => PasswordResets::TYPE_EMAIL,
                'code'       => $token,
                'created_at' => Carbon::now()
            ]);

            // отправить email  новым паролем
            $objData       = new \stdClass();
            $objData->link = url('/password-reset/token/' . $token);

            Mail::to($user->email)->send(new ResetPasswordEmail($objData));

            return redirect()->route('auth.password.email-response');
        }
    }

    public function resetByEmailResponse()
    {
        return view('auth.passwords.email_response', []);
    }

    public function resetByPhone(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('auth.passwords.phone', []);
        } elseif ($request->method() === 'POST') {
            /* @var $user User */
            $user = User::query()->where('phone', $request->get('phone'))->first();

            if (!$user)
                return redirect()->back()->with('error', 'Користувача не знайдено!');

            $code = rand(100000, 999999);

            PasswordResets::query()
                ->where('type', PasswordResets::TYPE_PHONE)
                ->where('field', $request->get('email'))
                ->delete();

            PasswordResets::insert([
                'field'      => $request->get('phone'),
                'type'       => PasswordResets::TYPE_PHONE,
                'code'       => $code,
                'created_at' => Carbon::now()
            ]);

            /* Send sms */
            $sended = TurboSMS::sendMessages($user->phone, 'Код підтвердження: ' . $code, 'sms');

            return redirect()->route('auth.password-reset.code-input');
        }
    }

    public function resetByPhoneCodeInput(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('auth.passwords.phone_input_code', []);
        } elseif ($request->method() === 'POST') {
            /* @var $model PasswordResets */
            $model = PasswordResets::query()
                ->where('type', PasswordResets::TYPE_PHONE)
                ->where('code', $request->get('code'))
                ->first();

            if (!$model)
                abort(404);

            $token = base64_encode('*code*' . $request->get('code') . '*code*' . $model->field . '*code*' . PasswordResets::TYPE_PHONE);

            return redirect('/password-change/' . $token);
        }
    }

    public function changePasswordGet($token)
    {
        $fields = explode('*code*', base64_decode($token));

        return view('auth.passwords.new_password_form', [
            'code'  => $fields[1],
            'field' => $fields[2],
            'type'  => $fields[3]
        ]);
    }

    public function changePassword(PasswordResetRequest $request)
    {
        /* @var $model PasswordResets */
        $model = PasswordResets::query()
            ->where('type', $request->get('type'))
            ->where('code', $request->get('code'))
            ->where('field', $request->get('field'))
            ->first();

        if (!$model)
            abort(404);

        $typeField = $request->get('type') == PasswordResets::TYPE_EMAIL ? 'email' : 'phone';

        $user = User::query()
            ->where($typeField, $request->get('field'))
            ->first();

        if (!$user)
            abort(404);

        $user->password = Hash::make($request->get('password'));
        $user->save();

        PasswordResets::query()
            ->where('type', $request->get('type'))
            ->where('code', $request->get('code'))
            ->where('field', $request->get('field'))
            ->delete();

        Auth::attempt(['email' => $user->email, 'password' => $request->get('password'), 'status' => User::STATUS_REGISTER]);

        return redirect()->route('profile')->with('success', 'Пароль успішно змінено!');
    }

}
