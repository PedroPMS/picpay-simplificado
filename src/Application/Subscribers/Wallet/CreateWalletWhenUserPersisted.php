<?php

namespace Picpay\Application\Subscribers\Wallet;

use Picpay\Application\Controllers\Wallet\Create;
use Picpay\Domain\Events\User\UserWasPersisted;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class CreateWalletWhenUserPersisted implements DomainEventSubscriberInterface
{
    public function __construct(private readonly Create $createWallet)
    {
    }

    public function __invoke(UserWasPersisted $event): void
    {
        $userId = UserId::fromValue($event->id);
        $this->createWallet->createWallet($userId);
    }

    public static function subscribedTo(): array
    {
        return [
            UserWasPersisted::class,
        ];
    }
}
