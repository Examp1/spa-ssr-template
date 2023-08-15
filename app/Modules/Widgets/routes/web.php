<?php

use App\Modules\Widgets\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('widgets.uri_prefix', 'admin'),
    'as' => config('widgets.route_name_prefix', 'admin.'),
    'middleware' => config('widgets.middleware', []),
], function () {
    Route::post('widgets/delete-selected', [CRUDController::class, 'deleteSelected'])->name('widgets.delete-selected');
    Route::get('widgets/copy/{from_id}/{to_lang}', [CRUDController::class,'copy']);
    Route::get('widgets/copy2/{id}', [CRUDController::class,'copy2']);
    Route::resource('widgets', CRUDController::class)->except('show');
});
