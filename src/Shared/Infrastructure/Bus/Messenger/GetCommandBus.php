<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;

final class GetCommandBus implements GetCommandBusInterface
{
    public function getCommandBus(): CommandBusInterface
    {
        return app(CommandBusInterface::class);
    }
}
