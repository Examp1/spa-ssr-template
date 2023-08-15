<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscribes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogSubscribeController extends Controller
{
    public function index(Request $request)
    {
        $recaptcha_secret = env('GOOGLE_RECAPTCHA_SECRET_KEY');

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $request->get('g-recaptcha-response') . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

        $response = json_decode($response, true);

        if ($response["success"] == true) {
            if ($request->has('email') && filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                try {
                    Subscribes::create(['email' => $request->get('email')]);
                } catch (\Throwable $e) {
                    Log::error($e);
                }
            }
        } else {
            return redirect()->back()->with('error', 'error');
        }

        return redirect()->back()->with('success', 'subscribe');
    }
}
