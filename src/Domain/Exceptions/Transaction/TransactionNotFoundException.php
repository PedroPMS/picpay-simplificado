<?php

namespace Picpay\Domain\Exceptions\Transaction;

use Picpay\Shared\Domain\DomainException;
use Throwable;

final class TransactionNotFoundException extends DomainException
{
    public function __construct($message = '', $code = 404, Throwable $previous = null)
    {
        $message = '' === $message ? 'Transaction not found' : $message;

        parent::__construct($message, $code, $previous);
    }
}
