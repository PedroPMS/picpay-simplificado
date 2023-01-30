<?php

namespace Picpay\Application\Controllers\Transaction\Debit;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;

class DebitTransactionCommand implements CommandInterface
{
    public function __construct(public readonly string $transactionId)
    {
    }
}
