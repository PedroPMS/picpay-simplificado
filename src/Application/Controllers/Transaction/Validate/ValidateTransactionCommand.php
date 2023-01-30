<?php

namespace Picpay\Application\Controllers\Transaction\Validate;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;

class ValidateTransactionCommand implements CommandInterface
{
    public function __construct(public readonly string $transactionId)
    {
    }
}
