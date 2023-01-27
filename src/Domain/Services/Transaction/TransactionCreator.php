<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class TransactionCreator
{
    public function __construct(
        private readonly TransactionRepository $repository,
        private readonly GetEventBusInterface $eventBus
    ) {
    }

    public function createTransaction(
        TransactionId $id,
        UserId $payerId,
        UserId $payeeId,
        TransactionValue $value,
        TransactionStatus $status
    ): void {
        $transaction = Transaction::create($id, $payerId, $payeeId, $value, $status);

        $this->repository->create($transaction);
        $transaction->transactionWasCreated();

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }
}
