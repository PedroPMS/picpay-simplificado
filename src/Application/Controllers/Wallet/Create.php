<?php

namespace Picpay\Application\Controllers\Wallet;

use Picpay\Domain\Services\Wallet\WalletCreator;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\Wallet\WalletAmount;
use Picpay\Domain\ValueObjects\Wallet\WalletId;
use Picpay\Shared\Domain\UuidGeneratorInterface;

class Create
{
    public function __construct(
        private readonly WalletCreator $walletCreator,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function createWallet(UserId $userId): void
    {
        $walletId = WalletId::fromValue($this->uuidGenerator->generate());
        $walletAmount = WalletAmount::fromValue(0);

        $this->walletCreator->createWallet($walletId, $walletAmount, $userId);
    }
}
