<?php

namespace Picpay\Domain\Exceptions\Transaction;

use Picpay\Shared\Domain\DomainException;
use Throwable;

final class ShopkeeperCantStartTransactionException extends DomainException
{
    public function __construct($message = '', $code = 422, Throwable $previous = null)
    {
        $message = '' === $message ? "Shopkeeper can't start a transaction." : $message;

        parent::__construct($message, $code, $previous);
    }
}
