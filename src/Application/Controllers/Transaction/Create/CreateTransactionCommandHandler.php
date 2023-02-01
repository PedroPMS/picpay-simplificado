<?php

namespace Picpay\Application\Controllers\Transaction\Create;

use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Services\Transaction\TransactionCreate;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;
use Picpay\Shared\Domain\UuidGeneratorInterface;

class CreateTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
        private readonly TransactionCreate $transactionCreator,
        private readonly GetEventBusInterface $eventBus,
    ) {
    }

    public function __invoke(CreateTransactionCommand $command): void
    {
        $id = TransactionId::fromValue($this->uuidGenerator->generate());
        $payerId = UserId::fromValue($command->payerId);
        $payeeId = UserId::fromValue($command->payeeId);
        $value = TransactionValue::fromValue($command->value);
        $status = TransactionStatus::CREATED;

        $transaction = $this->transactionCreator->createTransaction($id, $payerId, $payeeId, $value, $status);
        $transaction->transactionWasCreated();

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());

        // payee - find
        // transaction - start transaction
        // transaction - create transaction
        // payer - update payer wallet
        // payee - update payee wallet
        // transaction - end transaction
        // transaction dispatch payment event
    }
}
