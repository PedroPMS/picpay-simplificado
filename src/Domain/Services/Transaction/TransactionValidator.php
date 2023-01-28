<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\Transaction\TransactionUnautorizedException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class TransactionValidator
{
    public function __construct(
        private readonly UserFind $userFinder,
        private readonly TransactionUpdater $transactionUpdater,
        private readonly TransactionFind $transactionFinder,
        private readonly TransactionAuthorizer $transactionAuthorizer,
        private readonly PayerHasEnoughBalanceForTransaction $hasEnoughBalanceForTransaction,
        private readonly GetEventBusInterface $eventBus
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionNotFoundException
     */
    public function validateTransaction(TransactionId $id): void
    {
        $transaction = $this->transactionFinder->findTransaction($id);
        $payer = $this->userFinder->findUser(UserId::fromValue($transaction->payerId));

        try {
            if ($payer->isShopkeeper()) {
                throw new ShopkeeperCantStartTransactionException();
            }

            $this->hasEnoughBalanceForTransaction->checkPayerHasEnoughBalanceForTransaction($payer->id, $transaction->value);

            if (! $this->transactionAuthorizer->isAutorized()) {
                throw new TransactionUnautorizedException();
            }

            $this->updateValidatedTransaction($transaction);
        } catch (PayerDoesntHaveEnoughBalanceException|ShopkeeperCantStartTransactionException|TransactionUnautorizedException $exception) {
            $transaction->transactionWasRejected($exception->getMessage());
        }

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }

    private function updateValidatedTransaction(Transaction $transaction): void
    {
        $this->transactionUpdater->updateTransactionStatus($transaction->id, TransactionStatus::PENDING);
        $transaction->transactionWasValidated();
    }
}
