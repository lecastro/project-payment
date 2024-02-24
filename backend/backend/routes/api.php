<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('rest/payments')
    ->controller(App\Http\Controllers\PaymentController::class)
    ->group(function (): void {
        Route::post('/', 'storeAction')->name('payments.store');
        Route::get('/', 'indexAction')->name('payments.index');
        Route::get('/{id}', 'showAction')->name('payments.show');
        Route::put('/{id}/confirm', 'confirmAction')->name('payments.confirm');
        Route::put('/{id}/cancel', 'cancelAction')->name('payments.cancel');
});
