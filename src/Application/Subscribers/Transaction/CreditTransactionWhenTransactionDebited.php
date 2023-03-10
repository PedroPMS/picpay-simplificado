<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Application\Controllers\Transaction\Credit\CreditTransactionCommand;
use Picpay\Domain\Events\Transaction\TransactionDebited;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;

class CreditTransactionWhenTransactionDebited implements DomainEventSubscriberInterface
{
    public function __construct(private readonly GetCommandBusInterface $commandBus)
    {
    }

    public function __invoke(TransactionDebited $event): void
    {
        $command = new CreditTransactionCommand($event->id);

        $this->commandBus->getCommandBus()->dispatch($command);
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionDebited::class,
        ];
    }
}
