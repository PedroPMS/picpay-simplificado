<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Entities\User;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\User\UserId;

class UserFind
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUser(UserId $id): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
