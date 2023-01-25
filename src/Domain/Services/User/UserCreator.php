<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\User\UserName;
use Picpay\Domain\ValueObjects\User\UserPassword;

class UserCreator
{
    public function __construct(
        private readonly UserRepository         $repository,
        private readonly CheckUserAlreadyExists $checkUserAlreadyExists
    )
    {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function createUser(UserId $id, UserName $name, UserEmail $email, UserCpf $cpf, UserPassword $password, UserType $type): User
    {
        $this->checkUserAlreadyExists->checkUserExists($email, $cpf);

        $user = User::create($id, $name, $email, $cpf, $password, $type);
        $this->repository->create($user);

        return $user;
    }
}
