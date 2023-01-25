<?php

namespace Picpay\User\Domain\Services\Wallet;

use Picpay\User\Domain\Entities\Wallet;
use Picpay\User\Domain\Repositories\WalletRepository;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\WalletAmount;
use Picpay\User\Domain\ValueObject\WalletId;

class WalletCreator
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    public function handle(WalletId $id, WalletAmount $amount, UserId $userId): Wallet
    {
        $wallet = Wallet::create($id, $amount, $userId);
        $this->repository->create($wallet);

        return $wallet;
    }
}
