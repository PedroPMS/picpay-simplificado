<?php

namespace Picpay\Shared\Domain\Bus\Event;

use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;

interface GetCommandBusInterface
{
    public function getCommandBus(): CommandBusInterface;
}
