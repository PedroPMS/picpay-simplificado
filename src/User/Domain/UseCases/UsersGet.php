<?php

namespace Picpay\User\Domain\UseCases;

use Picpay\User\Domain\UserRepository;
use Picpay\User\Domain\Users;

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
