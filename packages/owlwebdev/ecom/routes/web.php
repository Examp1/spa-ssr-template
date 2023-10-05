<?php

use Owlwebdev\Ecom\Http\Controllers\Admin\CategoryController;
use Owlwebdev\Ecom\Http\Controllers\Admin\OrderController;
use Owlwebdev\Ecom\Http\Controllers\Admin\ProductController;
use Owlwebdev\Ecom\Http\Controllers\Admin\ReviewController;
use Owlwebdev\Ecom\Http\Controllers\Admin\CouponController;
use Owlwebdev\Ecom\Http\Controllers\Admin\DiscountController;
use Owlwebdev\Ecom\Http\Controllers\Admin\AttributeGroupController;
use Owlwebdev\Ecom\Http\Controllers\Admin\AttributeController;
use Owlwebdev\Ecom\Http\Controllers\Admin\FilterController;
use Owlwebdev\Ecom\Http\Controllers\Admin\SizeGridController;
use Owlwebdev\Ecom\Http\Controllers\Admin\SearchHistoryController;
use Owlwebdev\Ecom\Http\Controllers\Admin\SyncController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'admin', 'middleware' => ['admin']],
    function () {
    Route::post('products/export', [SyncController::class, 'export'])->name('products.export');
    Route::post('products/import', [SyncController::class, 'import'])->name('products.import');
    /** Catalog **/
    Route::post('/attribute_groups/delete-selected', [AttributeGroupController::class, 'deleteSelected'])->name('attribute_groups.delete-selected');
    Route::post('/attribute_groups.sort', [AttributeGroupController::class, 'sort'])->name('attribute_groups.sort');
    Route::resource('/attribute_groups', AttributeGroupController::class);

    Route::get('/attributes/{attribute_id}/{lang}/values', [AttributeController::class, 'valuesList'])->name('attributes.values');
    Route::post('/attributes/delete-selected', [AttributeController::class, 'deleteSelected'])->name('attributes.delete-selected');
    Route::post('/attributes.sort', [AttributeController::class, 'sort'])->name('attributes.sort');
    Route::resource('/attributes', AttributeController::class);

    Route::get('/filters', [FilterController::class, 'index'])->name('filters.index');
    Route::get('/filters/{id}/edit', [FilterController::class, 'edit'])->name('filters.edit');
    Route::post('/filters/{id}/edit', [FilterController::class, 'update'])->name('filters.update');

    Route::post('/categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.delete-selected');
    Route::post('/categories/meta/generate', [CategoryController::class, 'metaGenerate'])->name('categories.meta-generate');
    Route::get('/categories/copy/{id}', [CategoryController::class, 'copy'])->name('categories.copy');
    Route::resource('/categories', CategoryController::class);

    Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete-selected');
    Route::post('/products/meta/generate', [ProductController::class, 'metaGenerate'])->name('products.meta-generate');
    Route::post('/products/add-price', [ProductController::class, 'addPrice'])->name('products.add-price');
    Route::post('/products/copy-price', [ProductController::class, 'copyPrice'])->name('products.copy-price');
    Route::post('/products/save-price', [ProductController::class, 'savePrice'])->name('products.save-price');
    Route::post('/products/remove-price', [ProductController::class, 'removePrice'])->name('products.remove-price');
    Route::get('/products/copy/{id}', [ProductController::class, 'copy'])->name('products.copy');
    Route::resource('/products', ProductController::class);

    Route::post('/orders/delete-selected', [OrderController::class, 'deleteSelected'])->name('orders.delete-selected');
    Route::post('/orders/update-selected', [OrderController::class, 'updateSelected'])->name('orders.update-selected');
    Route::get('/orders/copy/{id}', [OrderController::class, 'copy'])->name('orders.copy');
    Route::delete('/orders/trash/{order}', [OrderController::class, 'trash'])->name('orders.trash');

    Route::post('/orders/products/add', [OrderController::class, 'addProduct'])->name('orders.products.store');
    Route::post('/orders/products/{order_product}', [OrderController::class, 'updateProduct'])->name('orders.products.update');
    Route::post('/orders/products/{order_product}/delete', [OrderController::class, 'deleteProduct'])->name('orders.products.delete');
    Route::get('/orders/invoice-create/{id}', [OrderController::class, 'invoiceCreate'])->name('orders.invoice-create');
    Route::get('/orders/stats', [OrderController::class, 'stats'])->name('orders.stats');
    Route::resource('/orders', OrderController::class);

    Route::post('/coupons/delete-selected', [CouponController::class, 'deleteSelected'])->name('coupons.delete-selected');
    Route::resource('/coupons', CouponController::class);

    Route::post('/discounts/delete-selected', [DiscountController::class, 'deleteSelected'])->name('discounts.delete-selected');
    Route::resource('/discounts', DiscountController::class);

    Route::post('/reviews/delete-selected', [ReviewController::class, 'deleteSelected'])->name('reviews.delete-selected');
    Route::resource('/reviews', ReviewController::class);

    Route::post('/size-grid/delete-selected', [SizeGridController::class, 'deleteSelected'])->name('size-grid.delete-selected');
    Route::get('/size-grid/copy/{id}', [SizeGridController::class, 'copy'])->name('size-grid.copy');
    Route::resource('/size-grid', SizeGridController::class);

    Route::delete('/search-history/destroy/{id}', [SearchHistoryController::class, 'destroy'])->name('search-history.destroy');
    Route::post('/search-history/delete-selected', [SearchHistoryController::class, 'deleteSelected'])->name('search-history.delete-selected');
    Route::get('/search-history', [SearchHistoryController::class, 'index'])->name('search-history.index');
    }
);
