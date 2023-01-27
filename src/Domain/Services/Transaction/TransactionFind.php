<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;

class TransactionFind
{
    public function __construct(private readonly TransactionRepository $repository)
    {
    }

    /**
     * @throws TransactionNotFoundException
     */
    public function findTransaction(TransactionId $id): Transaction
    {
        $transaction = $this->repository->findById($id);

        if (! $transaction) {
            throw new TransactionNotFoundException();
        }

        return $transaction;
    }
}
