<?php

namespace App\Http\Middleware;

//use App\Models\Langs;
use Closure;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public $mainLanguage;

    public $mainLanguageAdmin;

    public $languages;

    public function __construct()
    {
        $this->mainLanguage = config('app.locale');
        $this->mainLanguageAdmin = config('app.fallback_locale');
        $this->languages = config('app.locales');
    }

    public function getLocale()
    {
        $uri = request()->path();
        $segmentsURI = explode('/', $uri);

        if (!empty($segmentsURI[0]) && in_array($segmentsURI[0], $this->languages)) {
            if ($segmentsURI[0] != $this->mainLanguage) return $segmentsURI[0];
        }

        return null;
    }

    public function handle($request, Closure $next)
    {
        $locale = $this->getLocale();
        /*if($locale) App::setLocale($locale);
    else App::setLocale(self::$mainLanguage);*/

        if ($request->is('admin') || $request->is('admin/*')) {
            App::setLocale($this->mainLanguageAdmin);
        } elseif (!$locale) {
            App::setLocale($this->mainLanguage);
        } else {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
