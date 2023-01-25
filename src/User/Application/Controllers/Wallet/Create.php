<?php

namespace Picpay\User\Application\Controllers\Wallet;

use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\User\Domain\Services\Wallet\WalletCreator;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\WalletAmount;
use Picpay\User\Domain\ValueObject\WalletId;

class Create
{
    public function __construct(
        private readonly WalletCreator          $walletCreator,
        private readonly UuidGeneratorInterface $uuidGenerator
    )
    {
    }

    public function createWallet(UserId $userId): void
    {
        $walletId = WalletId::fromValue($this->uuidGenerator->generate());
        $walletAmount = WalletAmount::fromValue(0);

        $this->walletCreator->handle($walletId, $walletAmount, $userId);
    }
}
