<?php

namespace Picpay\Domain\Entities;

use JsonSerializable;
use Picpay\Domain\Enums\User\UserType;
use Picpay\Domain\Events\User\UserWasPersisted;
use Picpay\Domain\Exceptions\User\UserTypeException;
use Picpay\Domain\ValueObjects\User\UserCpf;
use Picpay\Domain\ValueObjects\User\UserEmail;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Domain\ValueObjects\User\UserName;
use Picpay\Domain\ValueObjects\User\UserPassword;
use Picpay\Shared\Domain\Aggregate\AggregateRoot;

class User extends AggregateRoot implements JsonSerializable
{
    public function __construct(
        public readonly UserId $id,
        public readonly UserName $name,
        public readonly UserEmail $email,
        public readonly UserCpf $cpf,
        public readonly UserPassword $password,
        public readonly UserType $type,
    ) {
    }

    /**
     * @throws UserTypeException
     */
    public static function fromPrimitives(string $id, string $name, string $email, string $cpf, string $password, string $type): self
    {
        return new self(
            UserId::fromValue($id),
            UserName::fromValue($name),
            UserEmail::fromValue($email),
            UserCpf::fromValue($cpf),
            UserPassword::fromValue($password),
            UserType::fromValue($type)
        );
    }

    public static function create(UserId $id, UserName $name, UserEmail $email, UserCpf $cpf, UserPassword $password, UserType $type): self
    {
        return new self($id, $name, $email, $cpf, $password, $type);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'password' => $this->password,
            'type' => $this->type,
        ];
    }

    public function isShopkeeper(): bool
    {
        return $this->type === UserType::SHOPKEEPER;
    }

    public function userWasPersisted(): void
    {
        $this->record(new UserWasPersisted($this->id, $this->name));
    }
}
