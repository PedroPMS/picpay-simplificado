<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\Shared\Infrastructure\RamseyUuidGenerator;
use Picpay\User\Domain\UserRepository;
use Picpay\User\Infrastructure\Database\UserEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UuidGeneratorInterface::class, RamseyUuidGenerator::class);
        $this->app->bind(UserRepository::class, UserEloquentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
