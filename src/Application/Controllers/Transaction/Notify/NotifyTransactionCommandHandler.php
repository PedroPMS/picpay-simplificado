<?php

namespace Picpay\Application\Controllers\Transaction\Notify;

use Picpay\Domain\Services\Transaction\TransactionNotify;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;

class NotifyTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly TransactionNotify $transactionNotify)
    {
    }

    public function __invoke(NotifyTransactionCommand $command): void
    {
        $id = TransactionId::fromValue($command->transactionId);
        $this->transactionNotify->notify($id);
    }
}
