<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegConfirmEmail;
use App\Models\User;
use App\Models\UserVerified;
use Carbon\Carbon;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegConfirmController extends Controller
{

    public function email()
    {
        if (is_null(Auth::user()->email_verified_at)) {
            return view('auth.email_verify', []);
        } else {
            return redirect()->route('profile');
        }
    }

    public function phone()
    {
        if (is_null(Auth::user()->phone_verified_at)) {
            return view('auth.phone_verify', []);
        } else {
            return redirect()->route('profile');
        }
    }

    public function addPhone(Request $request)
    {
        /* @var $user User */
        $user = User::query()->where('id', Auth::user()->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Користувача не знайдено!');
        }

        if($request->get('another')) {
            UserVerified::query()
                ->where('type', UserVerified::TYPE_PHONE)
                ->where('field',$user->phone)
                ->delete();
        }

        $user->phone = $request->get('phone');
        $user->save();

        $code = rand(100000, 999999);

        UserVerified::create([
            'field' => $user->phone,
            'code'  => $code,
            'type'  => UserVerified::TYPE_PHONE
        ]);

        /* Send sms */
        $sended = TurboSMS::sendMessages($user->phone, 'Код підтвердження: ' . $code, 'sms');

        return redirect()->route('auth.user-confirm-code');
    }

    public function phoneConfirmCode(Request $request)
    {
        /* @var $verified UserVerified */
        $verified = UserVerified::query()
            ->where('type', UserVerified::TYPE_PHONE)
            ->where('code', $request->get('code'))
            ->first();

        if (!$verified)
            return redirect()->back()->with('error', 'Не правельний код!');

        /* @var $user User */
        $user = User::query()->where('phone', $verified->field)
            ->where('status', User::STATUS_NOT_ACTIVE)
            ->first();

        if (!$user)
            return abort(404);

        $user->phone_verified_at = Carbon::now();

        if ($user->email_verified_at) {
            $user->status = User::STATUS_REGISTER;
        }

        $user->save();

        $verified->delete();

        return redirect()->route('profile')->with('success', 'Вітаемо з реєстрацєю! Подивіться наше відео, щоб краще зрозуміти як це працює.');
    }

    public function phoneCode()
    {
        return view('auth.phone_verify_input_code');
    }

    public function emailConfirm($token)
    {
        /* @var $verified UserVerified */
        $verified = UserVerified::query()
            ->where('type', UserVerified::TYPE_EMAIL)
            ->where('code', $token)
            ->first();

        if (!$verified)
            return abort(404);

        /* @var $user User */
        $user = User::query()->where('email', $verified->field)
            ->where('status', User::STATUS_NOT_ACTIVE)
            ->first();

        if (!$user)
            return abort(404);

        $user->email_verified_at = Carbon::now();
        $user->save();

        $verified->delete();

        return redirect()->route('profile');
    }

    public function resendEmail(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('auth.resend_email', []);
        } elseif ($request->method() === 'POST') {
            $token = Str::random(32);

            UserVerified::query()
                ->where('type', UserVerified::TYPE_EMAIL)
                ->where('field',$request->get('email'))->delete();
            UserVerified::create([
                'field' => $request->get('email'),
                'code'  => $token,
                'type'  => UserVerified::TYPE_EMAIL
            ]);

            // отправить email  новым паролем
            $objData           = new \stdClass();
            $objData->link     = url('/user-email-confirm/token/' . $token);

            Mail::to($request->get('email'))->send(new RegConfirmEmail($objData));

            return redirect()->route('profile');
        }
    }

    public function resendPhoneCode(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('auth.resend_phone_code', []);
        } elseif ($request->method() === 'POST') {
            dd($request->all());
        }
    }
}
