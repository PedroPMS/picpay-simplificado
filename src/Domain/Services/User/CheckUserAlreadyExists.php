<?php

namespace Picpay\Domain\Services\User;

use Picpay\Domain\Exceptions\User\UserAlreadyExistsException;
use Picpay\Domain\Repositories\UserRepository;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;

class CheckUserAlreadyExists
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function checkUserExists(UserEmail $email, UserCpf $cpf, ?UserId $id = null): void
    {
        if ($this->repository->findByEmail($email, $id)) {
            throw UserAlreadyExistsException::emailAlreadyExists();
        }

        if ($this->repository->findByCpf($cpf, $id)) {
            throw UserAlreadyExistsException::cpfAlreadyExists();
        }
    }
}
