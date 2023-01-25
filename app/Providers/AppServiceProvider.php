<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Infrastructure\Providers\DomainServiceProvider;
use Picpay\Presentation\Http\Routes\Router;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Domain\Bus\Query\QueryBusInterface;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerCommandBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerEventBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerQueryBus;

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
        $this->app->bind(
            EventBusInterface::class,
            function ($app) {
                return new MessengerEventBus($app->tagged('domain_event_subscriber'));
            }
        );

        $this->app->bind(
            QueryBusInterface::class,
            function ($app) {
                return new MessengerQueryBus($app->tagged('query_handler'));
            }
        );

        $this->app->bind(
            CommandBusInterface::class,
            function ($app) {
                return new MessengerCommandBus($app->tagged('command_handler'));
            }
        );
    }
}
