<?php

namespace Picpay\Transaction\Presentation\Http\Routes;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Picpay\Transaction\Presentation\Http\Controllers\TransactionController;

class TransactionRouter extends RouteServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Route::middleware(['web'])->prefix('transaction')->name('transaction.')->group(function () {
            Route::post('', [TransactionController::class, 'store']);
        });
    }
}
