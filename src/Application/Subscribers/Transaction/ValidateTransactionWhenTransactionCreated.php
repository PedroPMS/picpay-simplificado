<?php

namespace Picpay\Application\Subscribers\Transaction;

use Picpay\Domain\Events\Transaction\TransactionCreated;
use Picpay\Domain\Exceptions\Transaction\TransactionNotFoundException;
use Picpay\Domain\Exceptions\User\UserNotFoundException;
use Picpay\Domain\Exceptions\Wallet\WalletNotFoundException;
use Picpay\Domain\Services\Transaction\TransactionValidator;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class ValidateTransactionWhenTransactionCreated implements DomainEventSubscriberInterface
{
    public function __construct(private readonly TransactionValidator $validator)
    {
    }

    /**
     * @throws UserNotFoundException
     * @throws WalletNotFoundException
     * @throws TransactionNotFoundException
     */
    public function __invoke(TransactionCreated $event): void
    {
        $id = TransactionId::fromValue($event->id);
        dd($id);
        $this->validator->validateTransaction($id);
    }

    public static function subscribedTo(): array
    {
        return [
            TransactionCreated::class,
        ];
    }
}
