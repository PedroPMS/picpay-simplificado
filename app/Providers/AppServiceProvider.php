<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\Shared\Infrastructure\RamseyUuidGenerator;
use Picpay\User\Domain\Repositories\UserRepository;
use Picpay\User\Infrastructure\Repositories\UserEloquentRepository;
use Picpay\User\Presentation\Http\Routes\UserRouter;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UuidGeneratorInterface::class => RamseyUuidGenerator::class,
        UserRepository::class => UserEloquentRepository::class,
    ];

    public function register(): void
    {
        //Route Providers
        $this->app->register(UserRouter::class);
    }
}
