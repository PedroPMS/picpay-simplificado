<?php

namespace Picpay\Domain\Repositories;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;

interface TransactionRepository
{
    public function findById(TransactionId $id): ?Transaction;

    public function create(Transaction $transaction): void;

    public function update(Transaction $transaction): void;
}
