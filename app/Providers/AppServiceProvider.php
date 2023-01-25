<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Domain\Bus\Query\QueryBusInterface;
use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerCommandBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerEventBus;
use Picpay\Shared\Infrastructure\Bus\Messenger\MessengerQueryBus;
use Picpay\Shared\Infrastructure\RamseyUuidGenerator;
use Picpay\Transaction\Infrastructure\Providers\TransactionServiceProvider;
use Picpay\Transaction\Presentation\Http\Routes\TransactionRouter;
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
        //Domain Providers
        $this->app->register(TransactionServiceProvider::class);

        //Route Providers
        $this->app->register(UserRouter::class);
        $this->app->register(TransactionRouter::class);

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
