<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionUnautorizedException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;

class TransactionValidate
{
    public function __construct(
        private readonly TransactionAuthorizer $transactionAuthorizer,
        private readonly PayerHasEnoughBalanceForTransaction $hasEnoughBalanceForTransaction
    ) {
    }

    /**
     * @throws ShopkeeperCantStartTransactionException
     * @throws PayerDoesntHaveEnoughBalanceException
     * @throws WalletNotFoundException
     * @throws TransactionUnautorizedException
     */
    public function validateTransaction(User $payer, Transaction $transaction): void
    {
        if ($payer->isShopkeeper()) {
            throw new ShopkeeperCantStartTransactionException();
        }

        $this->hasEnoughBalanceForTransaction->checkPayerHasEnoughBalanceForTransaction($payer->id, $transaction->value);

        if (! $this->transactionAuthorizer->isAutorized()) {
            throw new TransactionUnautorizedException();
        }
    }
}
