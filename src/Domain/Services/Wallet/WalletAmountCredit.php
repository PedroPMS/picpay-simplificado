<?php

namespace Picpay\Domain\Services\Wallet;

use Picpay\Domain\Entities\Wallet;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\Wallet\WalletAmount;

class WalletAmountCredit
{
    public function __construct(
        private readonly WalletRepository $repository,
        private readonly WalletFind $walletFinder,
    ) {
    }

    /**
     * @throws WalletNotFoundException
     */
    public function creditWalletAmount(UserId $userId, TransactionValue $value): Wallet
    {
        $wallet = $this->walletFinder->findWalletByUser($userId);

        $newWalletAmount = WalletAmount::fromValue($wallet->amount->value() + $value->value());
        $newWalletBalance = Wallet::create($wallet->id, $newWalletAmount, $userId);
        $this->repository->update($newWalletBalance);

        return $wallet;
    }
}
