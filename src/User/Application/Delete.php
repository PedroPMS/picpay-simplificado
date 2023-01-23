<?php

namespace Picpay\User\Application;

use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Services\UserDeletor;
use Picpay\User\Domain\ValueObject\UserId;

class Delete
{
    public function __construct(private readonly UserDeletor $userDeletor)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUser(string $id): void
    {
        $userId = UserId::fromValue($id);
        $this->userDeletor->handle($userId);
    }
}
