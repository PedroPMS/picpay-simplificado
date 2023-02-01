<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;

class TransactionCreate
{
    public function __construct(private readonly TransactionRepository $repository)
    {
    }

    public function createTransaction(
        TransactionId $id,
        UserId $payerId,
        UserId $payeeId,
        TransactionValue $value,
        TransactionStatus $status
    ): Transaction {
        $transaction = Transaction::create($id, $payerId, $payeeId, $value, $status);

        $this->repository->create($transaction);

        return $transaction;
    }
}
