<?php

namespace Picpay\User\Domain\Services;

use Picpay\User\Domain\Entities\User;
use Picpay\User\Domain\Enums\UserType;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Repositories\UserRepository;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\UserName;
use Picpay\User\Domain\ValueObject\UserPassword;

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
    public function handle(UserId $id, UserName $name, UserEmail $email, UserCpf $cpf, UserPassword $password, UserType $type): User
    {
        $this->checkUserAlreadyExists->handle($email, $cpf);

        $user = User::create($id, $name, $email, $cpf, $password, $type);
        $this->repository->create($user);

        return $user;
    }
}
