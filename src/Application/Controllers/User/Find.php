<?php

namespace Picpay\Application\Controllers\User;

use Picpay\Application\Resources\User\UserResponse;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\ValueObjects\User\UserId;

class Find
{
    public function __construct(private readonly UserFind $userFind)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUser(string $id): UserResponse
    {
        $userId = UserId::fromValue($id);
        $user = $this->userFind->findUser($userId);

        return UserResponse::fromUser($user);
    }
}
