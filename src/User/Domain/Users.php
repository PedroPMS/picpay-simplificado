<?php

namespace Picpay\User\Domain;

use Picpay\Shared\Domain\AbstractCollection;

final class Users extends AbstractCollection
{
    protected function type(): string
    {
        return User::class;
    }
}
