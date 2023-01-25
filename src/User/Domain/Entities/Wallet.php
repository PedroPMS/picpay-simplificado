<?php

namespace Picpay\User\Domain\Entities;

use JsonSerializable;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\WalletAmount;
use Picpay\User\Domain\ValueObject\WalletId;

class Wallet implements JsonSerializable
{
    public function __construct(
        public readonly WalletId     $id,
        public readonly WalletAmount $amount,
        public readonly UserId       $userId
    )
    {
    }

    public static function fromPrimitives(string $id, int $amount, string $userId): self
    {
        return new self(
            WalletId::fromValue($id),
            WalletAmount::fromValue($amount),
            UserId::fromValue($userId),
        );
    }

    public static function create(WalletId $id, WalletAmount $amount, UserId $userId): self
    {
        return new self($id, $amount, $userId);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount->value(),
            'user_id' => $this->userId,
        ];
    }
}
