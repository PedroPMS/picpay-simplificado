<?php

namespace Picpay\Application\Controllers\Transaction\Create;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;

class CreateTransactionCommand implements CommandInterface
{
    public function __construct(
        public readonly string $payerId,
        public readonly string $payeeId,
        public readonly int $value,
    )
    {
    }
}
