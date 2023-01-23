<?php

namespace Picpay\User\Application;

use Picpay\User\Application\Resources\UserResponse;
use Picpay\User\Domain\Enums\UserType;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Domain\Services\UserUpdater;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\UserName;

class Update
{
    public function __construct(private readonly UserUpdater $userUpdater)
    {
    }

    /**
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function updateUser(string $id, string $name, string $email, string $cpf, string $type): UserResponse
    {
        $userId = UserId::fromValue($id);
        $userName = UserName::fromValue($name);
        $userEmail = UserEmail::fromValue($email);
        $userCpf = UserCpf::fromValue($cpf);
        $userType = UserType::fromValue($type);


        $user = $this->userUpdater->handle($userId, $userName, $userEmail, $userCpf, $userType);

        return UserResponse::fromUser($user);
    }
}
