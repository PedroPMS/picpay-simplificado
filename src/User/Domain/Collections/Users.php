<?php

namespace Picpay\User\Domain\Collections;

use Picpay\Shared\Domain\AbstractCollection;
use Picpay\User\Domain\Entities\User;

final class Users extends AbstractCollection
{
    protected function type(): string
    {
        return User::class;
    }
}
