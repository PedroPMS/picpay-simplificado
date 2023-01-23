<?php

namespace Picpay\User\Domain\UseCases;

use Picpay\User\Domain\Exceptions\UserAlreadyExistsException;
use Picpay\User\Domain\UserRepository;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;

class CheckUserAlreadyExists
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function handle(UserEmail $email, UserCpf $cpf, ?UserId $id = null): void
    {
        if ($this->repository->findByEmail($email, $id)) {
            throw UserAlreadyExistsException::emailAlreadyExists();
        }

        if ($this->repository->findByCpf($cpf, $id)) {
            throw UserAlreadyExistsException::cpfAlreadyExists();
        }
    }
}
