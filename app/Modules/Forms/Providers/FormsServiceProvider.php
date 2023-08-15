<?php

namespace App\Modules\Forms\Providers;

use App\Modules\Forms\Form;
use Illuminate\Contracts\Cache\Factory as CacheInterface;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class FormsServiceProvider extends ServiceProvider
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

        $this->definedPermissions();

        $this->loadRoutes();

        $this->loadTranslations();

        $this->loadViews();

        $this->publishedMigrations();

        $this->publishedViews();
    }

    /**
     * Defined permission for manipulation widgets
     */
    protected function definedPermissions()
    {
        foreach (config('forms.permissions', []) as $permission => $callback) {
            Gate::define($permission, $callback);
        }
    }

    /**
     * Merge config files
     *
     * @return void
     */
    protected function registerMergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/forms.php', 'forms');
    }

    /**
     * Register handlers
     *
     * @return void
     */
    protected function registerHandlers()
    {
        $this->app->bind('Form', function($app) {
            return new Form(
                $app,
                $app['config'],
                $app->make(Form::class),
                $app->make(CacheInterface::class)
            );
        });
    }

    /**
     * Publish config
     *
     * @return void
     */
    protected function publishedConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/forms.php' => config_path('forms.php'),
        ], 'config');
    }

    /**
     * Load routes
     *
     * @return void
     */
    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Load translations
     *
     * @return void
     */
    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'forms');
    }

    /**
     * Load views
     *
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'forms');
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
        if (!class_exists('CreateFormsTable')) {
            $timestamp = date('Y_m_d_His', time());

            foreach (glob(__DIR__ . '/../database/migrations/*.php') as $migration) {
                $pathParts = pathinfo($migration);

                $this->publishes([
                    $migration => database_path('migrations/' . $timestamp . '_' . $pathParts['basename']),
                ], 'migrations');
            }
        }
    }

    /**
     * Publish views
     *
     * @return void
     */
    protected function publishedViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/forms')
        ], 'views');
    }
}
