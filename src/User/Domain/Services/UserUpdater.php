<?php

namespace Picpay\User\Domain\Services;

use Picpay\User\Domain\Entities\User;
use Picpay\User\Domain\Enums\UserType;
use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Repositories\UserRepository;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\UserName;

class UserUpdater
{
    public function __construct(
        private readonly UserRepository             $repository,
        private readonly UserFind                   $userFind,
        private readonly CheckUserAlreadyExists $dataAlreadyExists
    )
    {
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function handle(UserId $id, UserName $name, UserEmail $email, UserCpf $cpf, UserType $type): User
    {
        $user = $this->userFind->handle($id);
        $this->dataAlreadyExists->handle($email, $cpf, $id);

        $user = User::create($id, $name, $email, $cpf, $user->password, $type);
        $this->repository->update($user);

        return $user;
    }
}
