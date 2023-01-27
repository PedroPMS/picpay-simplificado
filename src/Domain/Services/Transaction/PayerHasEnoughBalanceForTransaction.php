<?php

namespace Picpay\Domain\Services\Transaction;

use Picpay\Domain\Exceptions\Transaction\PayerDoesntHaveEnoughBalanceException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Wallet\WalletFind;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;

class PayerHasEnoughBalanceForTransaction
{
    public function __construct(private readonly WalletFind $walletFinder)
    {
    }

    /**
     * @throws WalletNotFoundException
     * @throws PayerDoesntHaveEnoughBalanceException
     */
    public function checkPayerHasEnoughBalanceForTransaction(UserId $payerId, TransactionValue $transactionValue): void
    {
        $wallet = $this->walletFinder->findWalletByUser($payerId);

        if ($wallet->amount->value() < $transactionValue->value()) {
            throw PayerDoesntHaveEnoughBalanceException::payerDoesntHaveEnoughBalance();
        }
    }
}
