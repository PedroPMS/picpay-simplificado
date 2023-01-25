<?php

namespace Picpay\User\Domain\Repositories;

use Picpay\User\Domain\Entities\Wallet;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\WalletId;

interface WalletRepository
{
    public function findById(WalletId $id): ?Wallet;
    public function findByUser(UserId $userId): ?Wallet;

    public function create(Wallet $wallet): void;

    public function update(Wallet $wallet): void;
}
