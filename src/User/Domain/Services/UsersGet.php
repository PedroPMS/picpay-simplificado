<?php

namespace Picpay\User\Domain\Services;

use Picpay\User\Domain\Collections\Users;
use Picpay\User\Domain\Repositories\UserRepository;

class UsersGet
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function handle(): Users
    {
        return $this->repository->getAll();
    }
}
