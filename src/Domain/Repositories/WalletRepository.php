<?php

namespace Picpay\Domain\Repositories;

use Picpay\Domain\Entities\Wallet;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\Wallet\WalletId;

interface WalletRepository
{
    public function findById(WalletId $id): ?Wallet;

    public function findByUser(UserId $userId): ?Wallet;

    public function create(Wallet $wallet): void;

    public function update(Wallet $wallet): void;
}
