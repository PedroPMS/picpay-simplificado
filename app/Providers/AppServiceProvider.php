<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Infrastructure\Providers\DomainServiceProvider;
use Picpay\Presentation\Http\Routes\Router;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Infrastructure\Bus\Messenger\IlluminateCommandBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\IlluminateEventBus;

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
        $this->app->singleton(CommandBusInterface::class, function ($app) {
            return new IlluminateCommandBus($app->tagged('command_handler'));
        });

        $this->app->singleton(EventBusInterface::class, function ($app) {
            return new IlluminateEventBus($app->tagged('domain_event_subscriber'));
        });
    }
}
