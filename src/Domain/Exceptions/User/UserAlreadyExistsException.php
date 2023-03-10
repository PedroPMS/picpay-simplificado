<?php

namespace Picpay\Domain\Exceptions\User;

use Picpay\Shared\Domain\DomainException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class UserAlreadyExistsException extends DomainException
{
    public function __construct($message = '', $code = 422, Throwable $previous = null)
    {
        $message = '' === $message ? 'User already exists' : $message;

        parent::__construct($message, $code, $previous);
    }

    public static function emailAlreadyExists(): self
    {
        return new self(
            'User with this email already exists.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function cpfAlreadyExists(): self
    {
        return new self(
            'User with this CPF already exists.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
