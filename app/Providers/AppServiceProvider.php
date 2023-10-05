<?php

namespace App\Providers;

//use App\Models\Langs;
use App\Modules\Setting\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Mail\Transport\EsputnikTransport;
use ESputnik\ESputnik;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Setting::class, function ($app) {
            return new Setting();
        });
        $this->app->alias(Setting::class, 'Setting');

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Blade::if('user', function () {
            return auth()->check() && auth()->user()->isUser();
        });

        Mail::extend('esputnik', function (array $config = []) {
            return new EsputnikTransport();
        });

        $this->includeHelpers();

        Paginator::useBootstrap();

        // config(['translatable.locales' => config('translatable.locales')] ?? ['en' => 'English']);
        // config(['translatable.locale' => config('app.locale')] ?? 'en');
        // config(['translatable.fallback_locale' => config('app.locale') ?? 'en']);
    }

    /**
     * Include helper functions
     *
     * @return void
     */
    protected function includeHelpers(): void
    {
        foreach (glob(__DIR__ . '/../Helpers/*.php') as $file) {
            require_once $file;
        }
    }
}
