<?php

namespace Picpay\User\Application;

use Picpay\User\Domain\UseCases\UsersGet;

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
