<?php

namespace Picpay\Domain\Enums\User;

use Picpay\Domain\Exceptions\User\UserTypeException;

enum UserType: string
{
    case COMMON = 'common';
    case SHOPKEEPER = 'shopkeeper';

    /**
     * @throws UserTypeException
     */
    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::COMMON->value => self::COMMON,
            self::SHOPKEEPER->value => self::SHOPKEEPER,
            default => throw UserTypeException::userTypeNotExists(),
        };
    }
}
