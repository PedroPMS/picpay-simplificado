<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\ValueObjects\Transaction\TransactionId;

class TransactionNotify
{
    public function __construct(
        private readonly TransactionNotifier $notifier,
        private readonly TransactionUpdate $transactionUpdater
    ) {
    }

    public function notify(TransactionId $transactionId): void
    {
        $this->notifier->sendNotification();
        $this->transactionUpdater->markAsNotified($transactionId);
    }
}
