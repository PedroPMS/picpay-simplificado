<?php

namespace Picpay\User\Domain\Services;

use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Repositories\UserRepository;
use Picpay\User\Domain\ValueObject\UserId;

class UserDeletor
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function handle(UserId $id): void
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repository->delete($id);
    }
}
