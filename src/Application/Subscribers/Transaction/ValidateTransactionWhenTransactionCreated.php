<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Application\Controllers\Transaction\Validate\ValidateTransactionCommand;
use Picpay\Domain\Events\Transaction\TransactionCreated;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class ValidateTransactionWhenTransactionCreated implements DomainEventSubscriberInterface
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function __invoke(TransactionCreated $event): void
    {
        $command = new ValidateTransactionCommand($event->id);

        $this->commandBus->dispatch($command);
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionCreated::class,
        ];
    }
}
