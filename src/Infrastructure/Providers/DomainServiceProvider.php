<?php

namespace Picpay\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Application\Controllers\Transaction\Create\CreateTransactionCommandHandler;
use Picpay\Application\Subscribers\Wallet\CreateWalletWhenUserPersisted;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Infrastructure\Repositories\Eloquent\UserEloquentRepository;
use Picpay\Infrastructure\Repositories\Eloquent\WalletEloquentRepository;
use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\Shared\Infrastructure\RamseyUuidGenerator;

class DomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->interfaceBinding();
        $this->handlerTagging();
        $this->eventTagging();
    }

    private function eventTagging()
    {
        $this->app->tag(
            CreateWalletWhenUserPersisted::class,
            'domain_event_subscriber'
        );
    }

    private function handlerTagging()
    {
        $this->app->tag(
            CreateTransactionCommandHandler::class,
            'command_handler'
        );
    }

    private function interfaceBinding()
    {
        $this->app->bind(UuidGeneratorInterface::class, RamseyUuidGenerator::class);
        $this->app->bind(UserRepository::class, UserEloquentRepository::class);
        $this->app->bind(WalletRepository::class, WalletEloquentRepository::class);
    }
}
