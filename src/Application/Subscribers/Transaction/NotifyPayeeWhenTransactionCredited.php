<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Application\Controllers\Transaction\Notify\NotifyTransactionCommand;
use Picpay\Domain\Events\Transaction\TransactionCredited;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;

class NotifyPayeeWhenTransactionCredited implements DomainEventSubscriberInterface
{
    public function __construct(private readonly GetCommandBusInterface $commandBus)
    {
    }

    public function __invoke(TransactionCredited $event): void
    {
        $command = new NotifyTransactionCommand($event->id);

        $this->commandBus->getCommandBus()->dispatch($command);
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionCredited::class,
        ];
    }
}
