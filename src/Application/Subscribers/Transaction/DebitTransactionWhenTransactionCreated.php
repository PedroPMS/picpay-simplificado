<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Application\Controllers\Transaction\Debit\DebitTransactionCommand;
use Picpay\Domain\Events\Transaction\TransactionCreated;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;

class DebitTransactionWhenTransactionCreated implements DomainEventSubscriberInterface
{
    public function __construct(private readonly GetCommandBusInterface $commandBus)
    {
    }

    public function __invoke(TransactionCreated $event): void
    {
        $command = new DebitTransactionCommand($event->id);

        $this->commandBus->getCommandBus()->dispatch($command);
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionCreated::class,
        ];
    }
}
