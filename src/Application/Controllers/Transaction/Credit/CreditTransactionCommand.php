<?php

namespace Picpay\Application\Controllers\Transaction\Credit;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;

class CreditTransactionCommand implements CommandInterface
{
    public function __construct(public readonly string $transactionId)
    {
    }
}
