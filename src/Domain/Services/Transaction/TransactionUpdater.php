<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;

class TransactionUpdater
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {
    }

    public function updateUser(
        TransactionId $id,
        UserId $payerId,
        UserId $payeeId,
        TransactionValue $value,
        TransactionStatus $status
    ): void {
        $transaction = Transaction::create($id, $payerId, $payeeId, $value, $status);
        $this->repository->update($transaction);
    }
}
