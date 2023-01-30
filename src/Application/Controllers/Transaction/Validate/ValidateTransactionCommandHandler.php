<?php

namespace Picpay\Application\Controllers\Transaction\Validate;

use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\TransactionValidator;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class ValidateTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TransactionValidator $validator,
        private readonly GetEventBusInterface $eventBus,
    ) {
    }

    /**
     * @throws TransactionNotFoundException
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     */
    public function __invoke(ValidateTransactionCommand $command): void
    {
        $id = TransactionId::fromValue($command->transactionId);
        $transaction = $this->validator->validateTransaction($id);

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }
}
