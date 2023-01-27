<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Domain\Bus\Event\GetEventBusInterface;

final class GetEventBus implements GetEventBusInterface
{
    public function getEventBus(): EventBusInterface
    {
        return app(EventBusInterface::class);
    }
}
