<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Domain\Events\Transaction\TransactionRejected;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class NotifyPayerWhenTransactionInvalidated implements DomainEventSubscriberInterface
{
    public function __invoke(TransactionRejected $event): void
    {
        dd($event, 'email enviado'); // todo send mail
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionRejected::class,
        ];
    }
}
