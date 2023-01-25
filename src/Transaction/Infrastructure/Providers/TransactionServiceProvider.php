<?php

namespace Picpay\Transaction\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Picpay\Transaction\Application\Create\CreateTransactionCommandHandler;

class TransactionServiceProvider extends ServiceProvider
{
    public function register()
    {
//        $this->app->bind(
//            BoardRepositoryInterface::class,
//            EloquentBoardRepository::class
//        );

        $this->app->tag(
            CreateTransactionCommandHandler::class,
            'command_handler'
        );

//        $this->app->tag(
//            SomethingWithCreatedBoardSubscriber::class,
//            'domain_event_subscriber'
//        );
    }
}
