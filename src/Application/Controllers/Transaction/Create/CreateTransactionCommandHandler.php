<?php

namespace Picpay\Application\Controllers\Transaction\Create;

use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Services\User\UserFind;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Infrastructure\Models\UserModel;
use Picpay\Shared\Domain\Bus\Command\CommandHandlerInterface;

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
        $payer = $this->userFinder->findUser(UserId::fromValue($command->payerId));
        UserModel::find('14a0691d-b5e3-4adf-89e5-4de2ca320f50')->update(['cpf' => 'secret123']);
        dd($payer->isShopkeeper());

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
