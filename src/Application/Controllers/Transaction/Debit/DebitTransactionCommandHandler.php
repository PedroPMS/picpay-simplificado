<?php

namespace Picpay\Application\Controllers\Transaction\Debit;

use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\TransactionDebit;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class DebitTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TransactionDebit $validator,
        private readonly GetEventBusInterface $eventBus,
    ) {
    }

    /**
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     */
    public function __invoke(DebitTransactionCommand $command): void
    {
        $id = TransactionId::fromValue($command->transactionId);
        $transaction = $this->validator->debitTransaction($id);

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }
}
