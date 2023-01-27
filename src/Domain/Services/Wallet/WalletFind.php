<?php

namespace Picpay\Domain\Services\Wallet;

use Picpay\Domain\Entities\Wallet;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Domain\ValueObjects\User\UserId;

class WalletFind
{
    public function __construct(private readonly WalletRepository $repository)
    {
    }

    /**
     * @throws WalletNotFoundException
     */
    public function findWalletByUser(UserId $userId): Wallet
    {
        $wallet = $this->repository->findByUserId($userId);

        if (! $wallet) {
            throw new WalletNotFoundException();
        }

        return $wallet;
    }
}
