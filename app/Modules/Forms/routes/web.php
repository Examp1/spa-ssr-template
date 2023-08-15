<?php

use App\Modules\Forms\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('forms.uri_prefix', 'admin'),
    'as' => config('forms.route_name_prefix', 'admin.'),
    'middleware' => config('forms.middleware', []),
], function () {
    Route::post('forms/add-field', [CRUDController::class, 'addField']);
    Route::post('forms/delete-selected', [CRUDController::class, 'deleteSelected'])->name('forms.delete-selected');
    Route::get('forms/copy/{from_id}/{to_lang}', [CRUDController::class,'copy']);
    Route::get('forms/copy2/{id}', [CRUDController::class,'copy2']);
    Route::resource('forms', CRUDController::class)->except('show');
});
