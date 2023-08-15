<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* User auth */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/user-email-confirm/token', [\App\Http\Controllers\Api\AuthController::class, 'emailConfirm']);
    Route::post('/user-phone-confirm', [\App\Http\Controllers\Api\AuthController::class, 'phoneConfirm']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
    Route::post('/user-profile', [\App\Http\Controllers\Api\AuthController::class, 'userProfile']);
    Route::post('/password-reset', [\App\Http\Controllers\Api\ResetController::class, 'reset']);
    Route::post('/password-reset/token', [\App\Http\Controllers\Api\ResetController::class, 'resetPostEmail']);
    Route::post('/password-change', [\App\Http\Controllers\Api\ResetController::class, 'passwordChange']);
});

/*Personal account*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'profile'
], function ($router) {
    Route::post('/edit', [\App\Http\Controllers\Api\ProfileController::class, 'edit']);
    Route::post('/user-phone-confirm', [\App\Http\Controllers\Api\ProfileController::class, 'phoneConfirm']);
    Route::post('/user-email-confirm/token', [\App\Http\Controllers\Api\ProfileController::class, 'emailConfirm']);
});

Route::POST('menu/get-by-ids', [\App\Http\Controllers\Api\MenuController::class,'getByIds']);
Route::POST('settings/all', [\App\Http\Controllers\Api\SettingsController::class,'getAll']);
Route::POST('page/get-by-slug', [\App\Http\Controllers\Api\PagesController::class,'getBySlug']);
Route::POST('page/all', [\App\Http\Controllers\Api\PagesController::class,'getAll']);

Route::POST('landing/all', [\App\Http\Controllers\Api\LandingController::class,'getAll']);
Route::POST('landing/get-by-slug', [\App\Http\Controllers\Api\LandingController::class,'getBySlug']);

Route::POST('get-main-page', [\App\Http\Controllers\Api\PagesController::class,'getMain']);

Route::POST('blog/articles/get-by-slug', [\App\Http\Controllers\Api\BlogArticlesController::class,'getBySlug']);
Route::POST('blog/articles/all', [\App\Http\Controllers\Api\BlogArticlesController::class,'getAll']);
Route::POST('blog/articles/get-by-category-slug', [\App\Http\Controllers\Api\BlogArticlesController::class,'category']);
Route::POST('blog/tags/all', [\App\Http\Controllers\Api\BlogArticlesController::class,'getTagsAll']);
Route::POST('blog/articles/get-by-tag-slug', [\App\Http\Controllers\Api\BlogArticlesController::class,'getByTagSlug']);

Route::POST('news/subscribe', [\App\Http\Controllers\Api\SubscribeController::class,'subscribe']);

Route::POST('request/send', [\App\Http\Controllers\Api\TelegramNoticeController::class,'send']);
Route::POST('file/upload', [\App\Http\Controllers\Api\TelegramNoticeController::class,'fileUpload']);
Route::POST('sitemap', [\App\Http\Controllers\Api\SitemapController::class,'index']);
