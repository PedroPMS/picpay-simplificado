<?php

namespace Picpay\Application\Controllers\Transaction\Credit;

use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\TransactionCredit;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class CreditTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TransactionCredit $transactionCredit,
        private readonly GetEventBusInterface $eventBus,
    ) {
    }

    /**
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     */
    public function __invoke(CreditTransactionCommand $command): void
    {
        $id = TransactionId::fromValue($command->transactionId);
        $transaction = $this->transactionCredit->creditTransaction($id);

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }
}
