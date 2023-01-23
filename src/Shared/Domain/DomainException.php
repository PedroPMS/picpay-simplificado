<?php

declare(strict_types=1);

namespace Picpay\Shared\Domain;

use Exception;

abstract class DomainException extends Exception
{
    protected $code = 400;
}
