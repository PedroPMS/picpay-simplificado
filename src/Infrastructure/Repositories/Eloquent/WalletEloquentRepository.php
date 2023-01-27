<?php

namespace Picpay\Infrastructure\Repositories\Eloquent;

use Picpay\Domain\Entities\Wallet;
use Picpay\Domain\Repositories\WalletRepository;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\Wallet\WalletId;
use Picpay\Infrastructure\Models\WalletModel;

final class WalletEloquentRepository implements WalletRepository
{
    public function __construct(private readonly WalletModel $model)
    {
    }

    public function create(Wallet $wallet): void
    {
        $this->model->newQuery()->create($wallet->jsonSerialize());
    }

    public function findById(WalletId $id): ?Wallet
    {
        /** @var WalletModel $walletModel */
        $walletModel = $this->model->newQuery()->find($id->value());

        if (! $walletModel) {
            return null;
        }

        return $this->toDomain($walletModel);
    }

    public function findByUserId(UserId $userId): ?Wallet
    {
        /** @var WalletModel $walletModel */
        $walletModel = $this->model
            ->newQuery()
            ->where('user_id', $userId->value())
            ->first();

        if (! $walletModel) {
            return null;
        }

        return $this->toDomain($walletModel);
    }

    public function update(Wallet $wallet): void
    {
        $walletModel = $this->model->newQuery()->find($wallet->id->value());
        $walletModel->update($wallet->jsonSerialize());
    }

    private function toDomain(WalletModel $walletModel): Wallet
    {
        return Wallet::fromPrimitives(
            $walletModel->id,
            $walletModel->amount,
            $walletModel->user_id,
        );
    }
}
