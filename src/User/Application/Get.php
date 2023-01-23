<?php

namespace Picpay\User\Application;

use Picpay\User\Application\Resources\UsersResponse;
use Picpay\User\Domain\Services\UsersGet;

class Get
{
    public function __construct(private readonly UsersGet $usersGet)
    {
    }

    public function getUsers(): UsersResponse
    {
        $users = $this->usersGet->handle();

        return UsersResponse::fromUsers($users);
    }
}
