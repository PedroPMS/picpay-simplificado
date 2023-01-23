<?php

namespace Picpay\User\Domain\Enums;

use Picpay\User\Domain\Exceptions\UserTypeException;

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
