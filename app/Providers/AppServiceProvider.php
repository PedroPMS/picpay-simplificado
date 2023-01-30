<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Infrastructure\Providers\DomainServiceProvider;
use Picpay\Presentation\Http\Routes\Router;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventStorageInterface;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;
use Picpay\Shared\Domain\DbTransactionInterface;
use Picpay\Shared\Infrastructure\Bus\Messenger\GetCommandBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\GetEventBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\IlluminateCommandBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\IlluminateEventBus;
use Picpay\Shared\Infrastructure\EloquentDbTransaction;
use Picpay\Shared\Infrastructure\Repositories\Eloquent\EventStorageEloquentRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Domain Providers
        $this->app->register(DomainServiceProvider::class);

        //Route Providers
        $this->app->register(Router::class);

        $this->busProviders();
    }

    private function busProviders(): void
    {
        $this->app->bind(EventStorageInterface::class, EventStorageEloquentRepository::class);

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            return new IlluminateCommandBus($app->tagged('command_handler'));
        });

        $this->app->singleton(EventBusInterface::class, function ($app) {
            return new IlluminateEventBus($app->tagged('domain_event_subscriber'));
        });

        $this->app->singleton(GetEventBusInterface::class, GetEventBus::class);
        $this->app->singleton(GetCommandBusInterface::class, GetCommandBus::class);
        $this->app->singleton(DbTransactionInterface::class, EloquentDbTransaction::class);
    }
}
