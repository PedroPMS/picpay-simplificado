<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionUnautorizedException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;

class TransactionValidate
{
    public function __construct(
        private readonly TransactionUpdate $transactionUpdater,
        private readonly TransactionAuthorizer $transactionAuthorizer,
        private readonly PayerHasEnoughBalanceForTransaction $hasEnoughBalanceForTransaction
    ) {
    }

    /**
     * @throws WalletNotFoundException
     */
    public function validateTransaction(User $payer, Transaction $transaction): bool
    {
        try {
            if ($payer->isShopkeeper()) {
                throw new ShopkeeperCantStartTransactionException();
            }

            $this->hasEnoughBalanceForTransaction->checkPayerHasEnoughBalanceForTransaction($payer->id, $transaction->value);

            if (! $this->transactionAuthorizer->isAutorized()) {
                throw new TransactionUnautorizedException();
            }

            return true;
        } catch (PayerDoesntHaveEnoughBalanceException|ShopkeeperCantStartTransactionException|TransactionUnautorizedException $exception) {
            $this->transactionUpdater->updateTransactionStatus($transaction->id, TransactionStatus::REJECTED);
            $transaction->transactionWasRejected($exception->getMessage());

            return false;
        }
    }
}
