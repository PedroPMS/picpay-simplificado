<?php

namespace Picpay\Domain\Services\Wallet;

use Picpay\Domain\Entities\Wallet;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\Wallet\WalletAmount;
use Picpay\Domain\ValueObjects\Wallet\WalletId;

class WalletAmountUpdater
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    public function createWallet(WalletId $id, WalletAmount $amount, UserId $userId): Wallet
    {
        $wallet = Wallet::create($id, $amount, $userId);
        $this->repository->update($wallet);

        return $wallet;
    }
}
