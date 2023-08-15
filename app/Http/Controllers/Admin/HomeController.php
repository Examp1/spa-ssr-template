<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.home');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.auth.login');
        } else {
            if (Auth::attempt([
                'email'    => $request->get('email'),
                'password' => $request->get('password')
            ],true)) {
                return redirect()->route('admin');
            }
            else {
                return redirect()->back()->with('error','Email or password is not correct!');
            }
        }
    }
}
