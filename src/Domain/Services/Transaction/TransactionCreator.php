<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Repositories\TransactionRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;

class TransactionCreator
{
    public function __construct(
        private readonly TransactionRepository $repository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function createTransaction(
        TransactionId $id,
        UserId $payerId,
        UserId $payeeId,
        TransactionValue $value,
        TransactionStatus $status
    ): void {
        $transaction = Transaction::create(TransactionId::fromValue('c30980a3-2d36-4553-b080-51fb736c59c9'), $payerId, $payeeId, $value, $status);

//        $this->repository->create($transaction);
        $transaction->transactionWasCreated();

        $this->eventBus->publish(...$transaction->pullDomainEvents());
    }
}
