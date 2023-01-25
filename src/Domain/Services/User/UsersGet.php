<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Collections\User\Users;
use Picpay\Domain\Repositories\UserRepository;

class UsersGet
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function getUsers(): Users
    {
        return $this->repository->getAll();
    }
}
