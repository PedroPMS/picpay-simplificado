<?php

namespace Picpay\Shared\Infrastructure\Bus;

use Picpay\Shared\Infrastructure\InfrastructureException;
use Throwable;

final class QueryNotRegisteredException extends InfrastructureException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "" === $message ? "Query not registered" : $message;
        parent::__construct($message, $code, $previous);
    }
}
