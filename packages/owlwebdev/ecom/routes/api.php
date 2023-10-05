<?php

use Illuminate\Support\Facades\Route;
use Owlwebdev\Ecom\Http\Controllers\Api\CartController;
use Owlwebdev\Ecom\Http\Controllers\Api\GridController;
use Owlwebdev\Ecom\Http\Controllers\Api\ReviewController;
use Owlwebdev\Ecom\Http\Controllers\Api\SearchController;
use Owlwebdev\Ecom\Http\Controllers\Api\ProductController;
use Owlwebdev\Ecom\Http\Controllers\Api\CategoryController;
use Owlwebdev\Ecom\Http\Controllers\Api\WishlistController;
use Owlwebdev\Ecom\Http\Controllers\Api\ProfileController;

/* User auth */
// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
//     Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
//     Route::post('/user-email-confirm/token', [\App\Http\Controllers\Api\AuthController::class, 'emailConfirm']);
//     Route::post('/user-phone-confirm', [\App\Http\Controllers\Api\AuthController::class, 'phoneConfirm']);
//     Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
//     Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
//     Route::post('/user-profile', [\App\Http\Controllers\Api\AuthController::class, 'userProfile']);
//     Route::post('/password-reset', [\App\Http\Controllers\Api\ResetController::class, 'reset']);
//     Route::post('/password-reset/token', [\App\Http\Controllers\Api\ResetController::class, 'resetPostEmail']);
//     Route::post('/password-change', [\App\Http\Controllers\Api\ResetController::class, 'passwordChange']);
// });

/*Personal account*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'profile'
], function ($router) {
    Route::post('/show', [ProfileController::class, 'show']);
    Route::post('/orders', [ProfileController::class, 'showOrders']);
    Route::post('/return', [ProfileController::class, 'showReturn']);
    Route::post('/wishlist', [ProfileController::class, 'showWishlist']);
    Route::post('/wishlist/update', [WishlistController::class, 'update']);
    Route::post('/wishlist/sync', [WishlistController::class, 'sync']);
    // Route::post('/edit', [\App\Http\Controllers\Api\ProfileController::class, 'edit']);
    // Route::post('/user-phone-confirm', [\App\Http\Controllers\Api\ProfileController::class, 'phoneConfirm']);
    // Route::post('/user-email-confirm/token', [\App\Http\Controllers\Api\ProfileController::class, 'emailConfirm']);
});
Route::POST('search/add-to-history', [SearchController::class, 'addToHistory']);
Route::POST('search', [SearchController::class, 'getAll']);

Route::POST('category/get-by-slug', [CategoryController::class, 'getBySlug']);
Route::POST('category/all', [CategoryController::class, 'getAll']);

Route::POST('product/get-by-slug', [ProductController::class, 'getBySlug']);
Route::POST('product/all', [ProductController::class, 'getAll']);

Route::POST('cart/count', [CartController::class, 'count']);
Route::POST('cart/add', [CartController::class, 'add']);
Route::POST('cart/update', [CartController::class, 'update']);
Route::POST('cart/delete', [CartController::class, 'delete'])->name('cart.delete');
Route::POST('cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::POST('cart/check-user', [CartController::class, 'checkUser'])->name('cart.check');
Route::post('cart/check-user-email', [CartController::class, 'checkUserEmail'])->name('cart.checkmail');
Route::POST('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::POST('cart/complete', [CartController::class, 'complete'])->name('cart.complete');
Route::POST('cart/liqpay/callback', [CartController::class, 'liqPayCallback'])->name('liq.pay.callback');
Route::POST('cart/thankyou', [CartController::class, 'thankYou']);

Route::post('wishlist/show', [WishlistController::class, 'show']);

Route::POST('review/add', [ReviewController::class, 'add']);
Route::POST('review/get', [ReviewController::class, 'get']);

Route::POST('grid/get-by-slug', [GridController::class, 'getBySlug']);
