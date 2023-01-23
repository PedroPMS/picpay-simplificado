<?php

namespace Picpay\User\Application;

use Picpay\Shared\Domain\UuidGeneratorInterface;
use Picpay\User\Application\Resources\UserResponse;
use Picpay\User\Domain\Enums\UserType;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Domain\Services\UserCreator;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\UserName;
use Picpay\User\Domain\ValueObject\UserPassword;

class Create
{
    public function __construct(private readonly UserCreator $userCreator, private readonly UuidGeneratorInterface $uuidGenerator)
    {
    }

    /**
     * @throws UserTypeException
     * @throws UserAlreadyExistsException
     */
    public function createUser(string $name, string $email, string $cpf, string $password, string $type): UserResponse
    {
        $userId = UserId::fromValue($this->uuidGenerator->generate());
        $userName = UserName::fromValue($name);
        $userEmail = UserEmail::fromValue($email);
        $userCpf = UserCpf::fromValue($cpf);
        $userPassword = UserPassword::fromValue($password);
        $userType = UserType::fromValue($type);

        $user = $this->userCreator->handle($userId, $userName, $userEmail, $userCpf, $userPassword, $userType);
        return UserResponse::fromUser($user);
    }
}
