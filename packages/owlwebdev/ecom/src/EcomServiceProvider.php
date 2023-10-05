<?php

namespace Owlwebdev\Ecom;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Owlwebdev\Ecom\Commands\ProductsCodesFixCommand;
use Owlwebdev\Ecom\Commands\ProductsHotRecalculateCommand;
use Owlwebdev\Ecom\Commands\GenerateFeedCommand;
use Owlwebdev\Ecom\Http\Middleware\SpatiePermissionMiddleware;
use Owlwebdev\Ecom\Models\Order;
use Owlwebdev\Ecom\Observers\OrderObserver;

class EcomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //
    // }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware(['web'])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });

        Route::group($this->apiRouteConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });


        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ecom');

        // $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'ecom');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                // Catalog widgets
                __DIR__ . '/Modules/Widgets/Collections/Catalog/CategoriesWidget.php' => base_path('app/Modules/Widgets/Collections/Catalog/CategoriesWidget.php'),
                __DIR__ . '/Modules/Widgets/Collections/Catalog/ProductsWidget.php' => base_path('app/Modules/Widgets/Collections/Catalog/ProductsWidget.php'),
                __DIR__ . '/Modules/Widgets/resources/views/fields/main-categories-list.blade.php' => base_path('app/Modules/Widgets/resources/views/fields/main-categories-list.blade.php'),
                __DIR__ . '/Modules/Widgets/resources/views/fields/categories-list.blade.php' => base_path('app/Modules/Widgets/resources/views/fields/categories-list.blade.php'),
                __DIR__ . '/../resources/assets/images/widgets/categories.jpg' => public_path('images/widgets/categories.jpg'),
                // Products slider widget
                __DIR__ . '/Modules/Widgets/Collections/Catalog/ProductSliderWidget.php' => base_path('app/Modules/Widgets/Collections/Catalog/ProductSliderWidget.php'),
                __DIR__ . '/Modules/Widgets/resources/views/fields/products-list.blade.php' => base_path('app/Modules/Widgets/resources/views/fields/products-list.blade.php'),
                __DIR__ . '/../resources/assets/images/widgets/product-slider.jpg' => public_path('images/widgets/product-slider.jpg'),
                // Export
                __DIR__ . '/../resources/assets/js/im-export.js' => public_path('js/im-export.js'),
            ], 'ecom-files');

            // $this->publishes([
            //     __DIR__ . '/../resources/assets' => public_path('ecom'),
            // ], 'ecom-files');

            // In addition to publishing assets, we also publish the config
            // $this->publishes([
            //     __DIR__ . '/../config/ecom.php' => config_path('ecom.php'),
            // ], 'ecom-config');

            // Load migrations
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            // Or publish
            // $this->publishes([
            //     __DIR__ . '/../database/migrations/2023_01_01_100000_create_tasks_table.php' =>
            //     database_path('migrations/' . date('Y_m_d_His', time()) . '_create_tasks_table.php'),

            //     // More migration files here
            // ], 'migrations');

            // Publish Seeders
            $this->publishes([
                realpath(__DIR__ . '/../database/seeders/EcomSeeder.php') => database_path('seeders/EcomSeeder.php')
            ], 'ecom-seeder');

            // Load console commands
            $this->commands([
                ProductsCodesFixCommand::class,
                ProductsHotRecalculateCommand::class,
                GenerateFeedCommand::class,
            ]);
        }

        // Model observers
        Order::observe(OrderObserver::class);
    }

    protected function apiRouteConfiguration()
    {
        return [
            'prefix' => 'api',
            'middleware' => 'api',
        ];
    }
}
