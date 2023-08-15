const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/admin_menu.js', 'public/js')
    .js('resources/js/admin_menu_categories.js', 'public/js')
    .sass('resources/sass/admin/menu.scss', 'public/css/admin')
    .sass('resources/sass/admin/reStyle.scss', 'public/css/admin');
