<?php

namespace Picpay\Application\Controllers\Transaction\Notify;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;

class NotifyTransactionCommand implements CommandInterface
{
    public function __construct(public readonly string $transactionId)
    {
    }
}
