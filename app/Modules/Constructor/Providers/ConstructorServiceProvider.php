<?php

namespace App\Modules\Constructor\Providers;

use App\Modules\Constructor\Constructor;
use App\Modules\Constructor\Http\Middleware\ValidateFields;
use Illuminate\Support\ServiceProvider;

class ConstructorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMergeConfig();

        $this->registerHandlers();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->publishedConfig();

        $this->includeHelpers();

        $this->publishedMigrations();

        //$this->publishedViews();

        $this->loadTranslations();

        $this->loadViews();

        $this->pushMiddleware();
    }

    /**
     * Merge config files
     *
     * @return void
     */
    protected function registerMergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/constructor.php', 'constructor');
    }

    /**
     * Include helper functions
     *
     * @return void
     */
    protected function includeHelpers(): void
    {
        foreach (glob(__DIR__ . '/../helpers/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Publish config
     *
     * @return void
     */
    protected function publishedConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/constructor.php' => config_path('constructor.php'),
        ], 'config');
    }

    /**
     * Register handlers
     *
     * @return void
     */
    protected function registerHandlers(): void
    {
        $this->app->singleton(Constructor::class, function ($container) {
            return new Constructor($container, $container['config']);
        });
    }

    /**
     * Publish views
     *
     * @return void
     */
    protected function publishedViews()
    {
        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/constructor'),
        ], 'views');
    }

    /**
     * Load translations
     *
     * @return void
     */
    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'constructor');
    }

    /**
     * Load views
     *
     * @return void
     */
    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'constructor');
    }

    /**
     * Publish migrations
     *
     * Publication of migrations with automatic addition of call date and time to the name
     *
     * @return void
     */
    protected function publishedMigrations()
    {
        if (!class_exists('CreateConstructorsTable')) {
            $timestamp = date('Y_m_d_His', time());

            foreach (glob(__DIR__ . '/../database/migrations/*.php') as $migration) {
                $pathParts = pathinfo($migration);

                $this->publishes([
                    $migration => database_path('migrations' . DIRECTORY_SEPARATOR . $timestamp . '_' . $pathParts['basename']),
                ], 'migrations');
            }
        }
    }

    /**
     * Add Middleware in request for validation fields
     *
     * @return void
     */
    protected function pushMiddleware(): void
    {
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', ValidateFields::class);
    }
}
