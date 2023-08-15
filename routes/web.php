<?php

use App\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\Route;

/* @var $localeMiddleware LocaleMiddleware */

$localeMiddleware = app(LocaleMiddleware::class);

\Illuminate\Support\Facades\Auth::routes();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('redirect/{driver}', [\App\Http\Controllers\Api\AuthController::class, 'redirectToProvider']);
Route::get('{driver}/callback', [\App\Http\Controllers\Api\AuthController::class, 'handleProviderCallback']);
Route::get('s-login/{token}', [\App\Http\Controllers\Api\AuthController::class, 'sLogin']);

Route::get('/admin/cache-clear', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');

    return redirect()->back()->with('success', 'Кеш успешно очищено!');
})->name('cache-clear');

//lang. switcher
Route::get('setlocale/{lang}', function ($lang) use ($localeMiddleware) {

    $referer = \Illuminate\Support\Facades\Redirect::back()->getTargetUrl(); //URL предыдущей страницы
    $parse_url = parse_url($referer, PHP_URL_PATH); // URI предыдущей страницы

    //разбиваем на массив по разделителю
    $segments = explode('/', $parse_url);

    //Если URL (где нажали на переключение языка) содержал корректную метку языка
    if (in_array($segments[1], $localeMiddleware->languages)) {

        unset($segments[1]); //удаляем метку
    }

    //Добавляем метку языка в URL (если выбран не язык по-умолчанию)
    if ($lang != $localeMiddleware->mainLanguage) {
        array_splice($segments, 1, 0, $lang);
    }

    //формируем полный URL
    $url = request()->root() . implode("/", $segments);

    //если были еще GET-параметры - добавляем их
    if (parse_url($referer, PHP_URL_QUERY)) {
        $url = $url . '?' . parse_url($referer, PHP_URL_QUERY);
    }

    return redirect(rtrim($url, '/\\')); //Перенаправляем назад на ту же страницу

})->name('setlocale');

Route::any('/admin/login', [\App\Http\Controllers\Admin\HomeController::class, 'login'])->name('admin.login');

/*********************************************** ADMIN ****************************************************************/
Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    Route::post('/export-table', [\App\Http\Controllers\Admin\ExportTableController::class, 'export'])->name('export-table');
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin');
    Route::resource('/admins', \App\Http\Controllers\Admin\AdminsController::class);
    Route::post('/users/delete-selected', [\App\Http\Controllers\Admin\UserController::class, 'deleteSelected'])->name('users.delete-selected');
    Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('/pages/lang/copy', [\App\Http\Controllers\Admin\PageController::class, 'copyLang'])->name('pages.copy-lang');
    Route::post('/pages/delete-selected', [\App\Http\Controllers\Admin\PageController::class, 'deleteSelected'])->name('pages.delete-selected');
    Route::post('/pages/meta/generate', [\App\Http\Controllers\Admin\PageController::class, 'metaGenerate'])->name('pages.meta-generate');
    Route::get('/pages/copy/{id}', [\App\Http\Controllers\Admin\PageController::class, 'copy'])->name('pages.copy');
    Route::resource('/pages', \App\Http\Controllers\Admin\PageController::class);
    Route::post('menu/move', [\App\Http\Controllers\Admin\MenuController::class, 'move']);
    Route::post('menu/rebuild', [\App\Http\Controllers\Admin\MenuController::class, 'rebuild']);
    Route::post('menu/add-item', [\App\Http\Controllers\Admin\MenuController::class, 'addItem'])->name('menu.add-item');
    Route::post('menu/delete-menu', [\App\Http\Controllers\Admin\MenuController::class, 'deleteMenu'])->name('menu.delete-menu');
    Route::post('menu/add-menu', [\App\Http\Controllers\Admin\MenuController::class, 'addMenu'])->name('menu.add-menu');
    Route::resource('/menu', \App\Http\Controllers\Admin\MenuController::class);
    Route::get('settings/{tab}', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('add-notice')->where('tab', '[a-z0-9-]+');
    Route::post('settings/contacts/add', [\App\Http\Controllers\Admin\SettingsController::class, 'addContactBlock'])->name('settings.contacts.add');
    Route::post('settings/contacts/remove', [\App\Http\Controllers\Admin\SettingsController::class, 'removeContactBlock'])->name('settings.contacts.remove');
    Route::post('settings/save', [\App\Http\Controllers\Admin\SettingsController::class, 'save'])->name('settings.save');
    Route::post('widget/get-products-by-category', [\App\Http\Controllers\Admin\HomeController::class, 'widgetGetProductsByCategory'])->name('widget.get-products-by-category');
    Route::post('/landing/lang/copy', [\App\Http\Controllers\Admin\LandingController::class, 'copyLang'])->name('landing.copy-lang');
    Route::get('/landing/copy/{id}', [\App\Http\Controllers\Admin\LandingController::class, 'copy'])->name('landing.copy');
    Route::post('/landing/delete-selected', [\App\Http\Controllers\Admin\LandingController::class, 'deleteSelected'])->name('landing.delete-selected');
    Route::post('/landing/meta/generate', [\App\Http\Controllers\Admin\LandingController::class, 'metaGenerate'])->name('landing.meta-generate');
    Route::resource('/landing', \App\Http\Controllers\Admin\LandingController::class);

    /** BLOG **/
    Route::group(['prefix' => 'blog'], function () {
        Route::post('/tags/add-new-tag-ajax', [\App\Http\Controllers\Admin\BlogTagsController::class, 'addTag'])->name('blog.tags.add-tag-ajax');
        Route::post('/tags/lang/copy', [\App\Http\Controllers\Admin\BlogTagsController::class, 'copyLang'])->name('blog.tags.copy-lang');
        Route::post('/tags/delete-selected', [\App\Http\Controllers\Admin\BlogTagsController::class, 'deleteSelected'])->name('blog.tags.delete-selected');
        Route::post('/tags/meta/generate', [\App\Http\Controllers\Admin\BlogTagsController::class, 'metaGenerate'])->name('blog.tags.meta-generate');
        Route::get('/tags/copy/{id}', [\App\Http\Controllers\Admin\BlogTagsController::class, 'copy'])->name('blog.tags.copy');
        Route::resource('/tags', \App\Http\Controllers\Admin\BlogTagsController::class, ['as' => 'blog']);
        Route::post('/categories/lang/copy', [\App\Http\Controllers\Admin\BlogCategoriesController::class, 'copyLang'])->name('blog.categories.copy-lang');
        Route::post('/categories/delete-selected', [\App\Http\Controllers\Admin\BlogCategoriesController::class, 'deleteSelected'])->name('blog.categories.delete-selected');
        Route::post('/categories/meta/generate', [\App\Http\Controllers\Admin\BlogCategoriesController::class, 'metaGenerate'])->name('blog.categories.meta-generate');
        Route::get('/categories/copy/{id}', [\App\Http\Controllers\Admin\BlogCategoriesController::class, 'copy'])->name('blog.categories.copy');
        Route::resource('/categories', \App\Http\Controllers\Admin\BlogCategoriesController::class, ['as' => 'blog']);
        Route::post('/articles/lang/copy', [\App\Http\Controllers\Admin\BlogArticlesController::class, 'copyLang'])->name('articles.copy-lang');
        Route::post('/articles/delete-selected', [\App\Http\Controllers\Admin\BlogArticlesController::class, 'deleteSelected'])->name('articles.delete-selected');
        Route::post('/articles/meta/generate', [\App\Http\Controllers\Admin\BlogArticlesController::class, 'metaGenerate'])->name('articles.meta-generate');
        Route::get('/articles/copy/{id}', [\App\Http\Controllers\Admin\BlogArticlesController::class, 'copy'])->name('articles.copy');
        Route::resource('/articles', \App\Http\Controllers\Admin\BlogArticlesController::class);
        Route::post('/subscribe/delete-selected', [\App\Http\Controllers\Admin\BlogSubscribeController::class, 'deleteSelected'])->name('subscribe.delete-selected');
        Route::get('/subscribe', [\App\Http\Controllers\Admin\BlogSubscribeController::class, 'index'])->name('subscribe.index');
        Route::get('/subscribe/list', [\App\Http\Controllers\Admin\BlogSubscribeController::class, 'list'])->name('subscribe.list');
    });

    /** Access routs */
    Route::resource('/permission-groups', \App\Http\Controllers\Admin\PermissionGroupController::class);
    Route::resource('/permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::resource('/roles', \App\Http\Controllers\Admin\RolesController::class);

    Route::get('/multimedia/files', [\App\Http\Controllers\Admin\FileManagerController::class, 'file'])->name('multimedia.files');
    Route::get('/multimedia/images', [\App\Http\Controllers\Admin\FileManagerController::class, 'image'])->name('multimedia.images');
    Route::get('/multimedia/get-info', [\App\Http\Controllers\Admin\FileManagerController::class, 'getInfo'])->name('multimedia.get-info');


    Route::post('/mailgun-test/send', [\App\Http\Controllers\Admin\MailgunTestController::class, 'send'])->name('mailgun-test.send');
    Route::get('/mailgun-test', [\App\Http\Controllers\Admin\MailgunTestController::class, 'index'])->name('mailgun-test.index');
});
/************************************ END ADMIN ***********************************************************************/


/****************************************** PROFILE *******************************************************************/
Route::group(['prefix' => 'profile', 'middleware' => ['auth', 'profile']], function () {
    //    Route::get('/', [\App\Http\Controllers\Profile\HomeController::class, 'index'])->name('profile');
});
/***************************************** END PROFILE ****************************************************************/

/****************************************** FILE MANAGER **************************************************************/
/*  */
Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
/***************************************** END FILE MANAGER ***********************************************************/

/***************************** FRONT *******************************/
Route::group(['prefix' => $localeMiddleware->getLocale()], function () {
    Route::get('/user-email-confirm/token/{token}', [\App\Http\Controllers\Front\PageController::class, 'index2']);
    Route::get('/profile/user-email-confirm/token/{token}', [\App\Http\Controllers\Front\PageController::class, 'index2']);
    Route::get('/password-reset/token/{token}', [\App\Http\Controllers\Front\PageController::class, 'index2']);
    Route::get('/sitemap.xml', [\App\Http\Controllers\Front\HomeController::class, 'sitemap']);
    Route::post('/subscribe', [\App\Http\Controllers\Front\BlogSubscribeController::class, 'index'])->name('front.subscribe');
    Route::get('/news', [\App\Http\Controllers\Front\BlogController::class, 'index'])->name('blog');
    Route::get('/news/tag/{slug}', [\App\Http\Controllers\Front\BlogController::class, 'tag'])->where('slug', '.*');
    Route::get('/news/{stringfilter}', [\App\Http\Controllers\Front\BlogController::class, 'show'])->where('stringfilter', '.*');
    Route::get('/landing/{slug}', [\App\Http\Controllers\Front\LandingController::class, 'show'])->where('slug', '.*')->name('landing.show');
    Route::get('/{slug?}', [\App\Http\Controllers\Front\PageController::class, 'index'])->where('slug', '.*');
    Route::get('/', [\App\Http\Controllers\Front\PageController::class, 'index'])->name('main');
});
