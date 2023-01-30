<?php

namespace Picpay\Domain\Services\Transaction;

use Exception;
use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Transaction\ShopkeeperCantStartTransactionException;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\Transaction\TransactionUnautorizedException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\Services\Wallet\WalletAmountDebit;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\DbTransactionInterface;

class TransactionDebit
{
    public function __construct(
        private readonly UserFind $userFinder,
        private readonly TransactionUpdater $transactionUpdater,
        private readonly TransactionFind $transactionFinder,
        private readonly TransactionValidate $transactionValidator,
        private readonly WalletAmountDebit $walletDebiter,
        private readonly DbTransactionInterface $dbTransaction,
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionNotFoundException
     */
    public function debitTransaction(TransactionId $id): Transaction
    {
        $transaction = $this->transactionFinder->findTransaction($id);
        $payer = $this->userFinder->findUser(UserId::fromValue($transaction->payerId));

        try {
            $this->transactionValidator->validateTransaction($payer, $transaction);
            $this->debitPayerWallet($transaction, $payer);
        } catch (PayerDoesntHaveEnoughBalanceException|ShopkeeperCantStartTransactionException|TransactionUnautorizedException $exception) {
            $transaction->transactionWasRejected($exception->getMessage());
        }

        return $transaction;
    }

    /**
     * @throws WalletNotFoundException
     */
    private function debitPayerWallet(Transaction $transaction, User $payer): void
    {
        $this->dbTransaction->beginTransaction();

        try {
            $this->walletDebiter->debitWalletAmount($payer->id, $transaction->value);
            $this->transactionUpdater->updateTransactionStatus($transaction->id, TransactionStatus::DEBITED);
            $transaction->transactionWasDebited();
            $this->dbTransaction->commit();
        } catch (Exception $exception) {
            $this->dbTransaction->rollBack();
            throw $exception;
        }
    }
}
