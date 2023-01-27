<?php

namespace Picpay\Domain\Enums\Transaction;

use Picpay\Domain\Exceptions\Transaction\TransactionStatusException;

enum TransactionStatus: string
{
    case CREATED = 'created';
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case REJECTED = 'rejected';

    /**
     * @throws TransactionStatusException
     */
    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::CREATED->value => self::CREATED,
            self::PENDING->value => self::PENDING,
            self::SUCCEEDED->value => self::SUCCEEDED,
            self::REJECTED->value => self::REJECTED,
            default => throw TransactionStatusException::transactionStatusNotExists(),
        };
    }
}
