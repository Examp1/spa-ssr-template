<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('front.home.frontend', []);
    }

    public function sitemap()
    {
        $pathToFile = public_path() . '/sitemap.xml';

        if (!file_exists($pathToFile)) {
            abort(404);
        }

        return response()->file($pathToFile, ['Content-Type' => 'text/xml']);
    }
}
