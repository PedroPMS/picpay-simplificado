<?php

namespace Picpay\Application\Controllers\User;

use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Services\User\UserDeletor;
use Picpay\Domain\ValueObjects\User\UserId;

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
        $this->userDeletor->deleteUser($userId);
    }
}
