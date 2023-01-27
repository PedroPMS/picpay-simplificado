<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;

class TransactionUpdater
{
    public function __construct(
        private readonly TransactionRepository $repository,
    ) {
    }

    public function updateTransactionStatus(TransactionId $transactionId, TransactionStatus $newStatus): void
    {
        $this->repository->updateStatus($transactionId, $newStatus);
    }
}
