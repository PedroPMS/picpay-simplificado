<?php

namespace Picpay\Domain\Repositories;

use Picpay\Domain\Collections\User\Users;
use Picpay\Domain\Entities\User;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;

interface UserRepository
{
    public function getAll(): Users;

    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email, ?UserId $excludeId = null): ?User;

    public function findByCpf(UserCpf $cpf, ?UserId $excludeId = null): ?User;

    public function create(User $user): void;

    public function delete(UserId $id): void;

    public function update(User $user): void;
}
