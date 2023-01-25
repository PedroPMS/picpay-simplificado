<?php

namespace Picpay\Application\Controllers\User;

use Picpay\Application\Controllers\Wallet\Create as CreateWallet;
use Picpay\Application\Resources\User\UserResponse;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\Services\User\UserCreator;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\User\UserName;
use Picpay\Domain\ValueObjects\User\UserPassword;
use Picpay\Shared\Domain\UuidGeneratorInterface;

class Create
{
    public function __construct(
        private readonly UserCreator            $userCreator,
        private readonly CreateWallet           $createWallet,
        private readonly UuidGeneratorInterface $uuidGenerator
    )
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

        $user = $this->userCreator->createUser($userId, $userName, $userEmail, $userCpf, $userPassword, $userType);
        $this->createWallet->createWallet($userId);
        return UserResponse::fromUser($user);
    }
}
