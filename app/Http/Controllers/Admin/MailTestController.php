<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Log;
use Throwable;

class MailTestController extends Controller
{
    public function index()
    {
        return view('admin.mail-test.index');
    }

    public function send(Request $request)
    {
        $objData       = new \stdClass();
        $objData->email = $request->get('to');
        $objData->link = $request->get('to');

        try {
            Mail::to($request->get('to'))->send(new ResetPasswordEmail($objData));

            return redirect()->route('mail-test.index')->with('success', 'Success');
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->route('mail-test.index')->with('error', 'Error');
        }

    }
}
