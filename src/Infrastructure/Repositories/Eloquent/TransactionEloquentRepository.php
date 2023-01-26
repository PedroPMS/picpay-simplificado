<?php

namespace Picpay\Infrastructure\Repositories\Eloquent;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Exceptions\Transaction\TransactionStatusException;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Infrastructure\Models\TransactionModel;

final class TransactionEloquentRepository implements TransactionRepository
{
    public function __construct(private readonly TransactionModel $model)
    {
    }

    public function create(Transaction $transaction): void
    {
        $this->model->newQuery()->create($transaction->jsonSerialize());
    }

    /**
     * @throws TransactionStatusException
     */
    public function findById(TransactionId $id): ?Transaction
    {
        /** @var TransactionModel $transactionModel */
        $transactionModel = $this->model->newQuery()->find($id->value());

        if (! $transactionModel) {
            return null;
        }

        return $this->toDomain($transactionModel);
    }

    public function update(Transaction $transaction): void
    {
        $transactionModel = $this->model->newQuery()->find($transaction->id->value());
        $transactionModel->update($transaction->jsonSerialize());
    }

    /**
     * @throws TransactionStatusException
     */
    private function toDomain(TransactionModel $transactionModel): Transaction
    {
        return Transaction::fromPrimitives(
            $transactionModel->id,
            $transactionModel->payer_id,
            $transactionModel->payee_id,
            $transactionModel->value,
            $transactionModel->status,
        );
    }
}
