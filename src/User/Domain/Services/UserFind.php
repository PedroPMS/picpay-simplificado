<?php

namespace Picpay\User\Domain\Services;

use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Repositories\UserRepository;
use Picpay\User\Domain\User;
use Picpay\User\Domain\ValueObject\UserId;

class UserFind
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function handle(UserId $id): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
