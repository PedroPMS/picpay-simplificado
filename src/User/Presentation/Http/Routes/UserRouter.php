<?php

namespace Picpay\User\Presentation\Http\Routes;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Picpay\User\Presentation\Http\Controllers\UserController;

class UserRouter extends RouteServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['web'])->prefix('users')->name('users.')->group(function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'delete']);
        });
    }
}
