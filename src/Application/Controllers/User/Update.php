<?php

namespace Picpay\Application\Controllers\User;

use Picpay\Application\Resources\User\UserResponse;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\Services\User\UserUpdater;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\User\UserName;

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


        $user = $this->userUpdater->updateUser($userId, $userName, $userEmail, $userCpf, $userType);

        return UserResponse::fromUser($user);
    }
}
