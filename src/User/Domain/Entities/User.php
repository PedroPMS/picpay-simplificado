<?php

namespace Picpay\User\Domain\Entities;

use JsonSerializable;
use Picpay\User\Domain\Enums\UserType;
use Picpay\User\Domain\Exceptions\UserTypeException;
use Picpay\User\Domain\ValueObject\UserCpf;
use Picpay\User\Domain\ValueObject\UserEmail;
use Picpay\User\Domain\ValueObject\UserId;
use Picpay\User\Domain\ValueObject\UserName;
use Picpay\User\Domain\ValueObject\UserPassword;

class User implements JsonSerializable
{
    public function __construct(
        public readonly UserId       $id,
        public readonly UserName     $name,
        public readonly UserEmail    $email,
        public readonly UserCpf      $cpf,
        public readonly UserPassword $password,
        public readonly UserType     $type,
    )
    {
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
}
