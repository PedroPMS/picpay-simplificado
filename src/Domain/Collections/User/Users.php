<?php

namespace Picpay\Domain\Collections\User;

use Picpay\Domain\Entities\User;
use Picpay\Shared\Domain\AbstractCollection;

final class Users extends AbstractCollection
{
    protected function type(): string
    {
        return User::class;
    }
}
