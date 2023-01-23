<?php

namespace Picpay\User\Application;

use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\UseCases\UserFind;
use Picpay\User\Domain\ValueObject\UserId;

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
        $user = $this->userFind->handle($userId);

        return UserResponse::fromUser($user);
    }
}
