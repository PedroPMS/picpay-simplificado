<?php

namespace Picpay\Domain\Exceptions\User;

use Picpay\Shared\Domain\DomainException;
use Throwable;

final class UserNotFoundException extends DomainException
{
    public function __construct($message = '', $code = 404, Throwable $previous = null)
    {
        $message = '' === $message ? 'User not found' : $message;

        parent::__construct($message, $code, $previous);
    }
}
