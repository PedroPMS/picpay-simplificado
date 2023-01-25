<?php

namespace Picpay\Transaction\Application\Create;

use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Picpay\User\Domain\Entities\User;
use Picpay\User\Domain\Exceptions\UserNotFoundException;
use Picpay\User\Domain\Services\UserFind;
use Picpay\User\Domain\ValueObject\UserId;

class CreateTransactionCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly UserFind $userFinder)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(CreateTransactionCommand $command): void
    {
        // payer - find
        $payer = $this->userFinder->handle(UserId::fromValue($command->payerId));

        // payer - check type
        if ($payer->isShopkeeper()) {
            // throw
            dd(1);
        }

        // payer - check wallet
        // transaction - check autorized
        // payee - find
        // transaction - start transaction
        // transaction - create transaction
        // payer - update payer wallet
        // payee - update payee wallet
        // transaction - end transaction
        // transaction dispatch payment event

    }
}
