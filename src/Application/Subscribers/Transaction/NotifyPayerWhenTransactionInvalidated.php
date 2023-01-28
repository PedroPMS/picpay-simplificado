<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Domain\Events\Transaction\TransactionInvalidated;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class NotifyPayerWhenTransactionInvalidated implements DomainEventSubscriberInterface
{
    public function __invoke(TransactionInvalidated $event): void
    {
        dd($event, 'email enviado'); // todo send mail
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionInvalidated::class,
        ];
    }
}
