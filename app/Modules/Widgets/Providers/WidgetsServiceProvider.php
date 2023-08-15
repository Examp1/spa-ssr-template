<?php

namespace App\Modules\Widgets\Providers;

use App\Modules\Widgets\Widget;
use Illuminate\Contracts\Cache\Factory as CacheInterface;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class WidgetsServiceProvider extends ServiceProvider
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
        $this->definedPermissions();

        //$this->publishedConfig();

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
        foreach (config('widgets.permissions', []) as $permission => $callback) {
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
        $this->mergeConfigFrom(__DIR__ . '/../config/widgets.php', 'widgets');
    }

    /**
     * Register handlers
     *
     * @return void
     */
    protected function registerHandlers()
    {
        $this->app->bind('Widget', function($app) {
            return new Widget(
                $app,
                $app['config'],
                $app->make(Widget::class),
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
            __DIR__ . '/../config/widgets.php' => config_path('widgets.php'),
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
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'widgets');
    }

    /**
     * Load views
     *
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'widgets');
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
        if (!class_exists('CreateWidgetsTable')) {
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
            __DIR__ . '/../resources/views' => resource_path('views/vendor/widgets')
        ], 'views');
    }
}
