<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Entities\User;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\User\UserName;

class UserUpdater
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserFind $userFind,
        private readonly UserAlreadyExists $checkUserAlreadyExists
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws UserNotFoundException
     */
    public function updateUser(UserId $id, UserName $name, UserEmail $email, UserCpf $cpf, UserType $type): User
    {
        $user = $this->userFind->findUser($id);
        $this->checkUserAlreadyExists->checkUserExists($email, $cpf, $id);

        $user = User::create($id, $name, $email, $cpf, $user->password, $type);
        $this->repository->update($user);

        return $user;
    }
}
