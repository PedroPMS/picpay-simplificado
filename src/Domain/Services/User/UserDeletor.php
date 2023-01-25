<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\User\UserId;

class UserDeletor
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUser(UserId $id): void
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repository->delete($id);
    }
}
