<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

class TransactionValidator
{
    public function __construct(
        private readonly TransactionFind $transactionFinder,
        private readonly UserFind $userFinder,
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
            // todo check in provider
        } catch (PayerDoesntHaveEnoughBalanceException|ShopkeeperCantStartTransactionException $exception) {
            $transaction->transactionWasRejected($exception->getMessage());
        }

        $this->eventBus->getEventBus()->publish(...$transaction->pullDomainEvents());
    }
}
