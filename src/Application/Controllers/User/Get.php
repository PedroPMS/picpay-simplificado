<?php

namespace Picpay\Application\Controllers\User;

use Picpay\Application\Resources\User\UsersResponse;
use Picpay\Domain\Services\User\UsersGet;

class Get
{
    public function __construct(private readonly UsersGet $usersGet)
    {
    }

    public function getUsers(): UsersResponse
    {
        $users = $this->usersGet->getUsers();

        return UsersResponse::fromUsers($users);
    }
}
