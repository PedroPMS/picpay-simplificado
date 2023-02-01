<?php

namespace Picpay\Domain\Services\Transaction;

use Exception;
use Picpay\Domain\Entities\Transaction;
use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\Services\Wallet\WalletAmountCredit;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\DbTransactionInterface;

class TransactionCredit
{
    public function __construct(
        private readonly UserFind $userFinder,
        private readonly TransactionUpdate $transactionUpdater,
        private readonly TransactionFind $transactionFinder,
        private readonly WalletAmountCredit $walletCrediter,
        private readonly DbTransactionInterface $dbTransaction,
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionNotFoundException
     */
    public function creditTransaction(TransactionId $id): Transaction
    {
        $transaction = $this->transactionFinder->findTransaction($id);
        $payee = $this->userFinder->findUser(UserId::fromValue($transaction->payeeId));

        $this->creditPayeeWallet($transaction, $payee);

        return $transaction;
    }

    /**
     * @throws WalletNotFoundException
     */
    private function creditPayeeWallet(Transaction $transaction, User $payee): void
    {
        $this->dbTransaction->beginTransaction();

        try {
            $this->walletCrediter->creditWalletAmount($payee->id, $transaction->value);
            $this->transactionUpdater->updateTransactionStatus($transaction->id, TransactionStatus::SUCCEEDED);
            $transaction->transactionWasCredited();
            $this->dbTransaction->commit();
        } catch (Exception $exception) {
            $this->dbTransaction->rollBack();
            throw $exception;
        }
    }
}
