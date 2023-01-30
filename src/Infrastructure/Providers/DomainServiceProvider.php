<?php

namespace Picpay\Infrastructure\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Picpay\Application\Controllers\Transaction\Create\CreateTransactionCommandHandler;
use Picpay\Application\Controllers\Transaction\Validate\ValidateTransactionCommandHandler;
use Picpay\Application\Subscribers\Transaction\NotifyPayerWhenTransactionInvalidated;
use Picpay\Application\Subscribers\Transaction\ValidateTransactionWhenTransactionCreated;
use Picpay\Application\Subscribers\Wallet\CreateWalletWhenUserPersisted;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Domain\Services\Transaction\TransactionAuthorizer;
use Picpay\Infrastructure\Providers\Http\Transaction\TransactionAuthorizerClient;
use Picpay\Infrastructure\Repositories\Eloquent\TransactionEloquentRepository;
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

        $this->app->tag(
            NotifyPayerWhenTransactionInvalidated::class,
            'domain_event_subscriber'
        );

        $this->app->tag(
            ValidateTransactionWhenTransactionCreated::class,
            'domain_event_subscriber'
        );
    }

    private function handlerTagging()
    {
        $this->app->tag(
            CreateTransactionCommandHandler::class,
            'command_handler'
        );

        $this->app->tag(
            ValidateTransactionCommandHandler::class,
            'command_handler'
        );
    }

    private function interfaceBinding()
    {
        $this->app->bind(UuidGeneratorInterface::class, RamseyUuidGenerator::class);
        $this->app->bind(UserRepository::class, UserEloquentRepository::class);
        $this->app->bind(WalletRepository::class, WalletEloquentRepository::class);
        $this->app->bind(TransactionRepository::class, TransactionEloquentRepository::class);

        $this->app->bind(TransactionAuthorizer::class, function () {
            return new TransactionAuthorizerClient(new Client([
                'base_uri' => sprintf('https://run.mocky.io/%s/', TransactionAuthorizerClient::API_VERSION),
            ]));
        });
    }
}
