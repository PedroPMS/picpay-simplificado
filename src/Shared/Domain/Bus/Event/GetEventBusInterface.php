<?php

namespace Picpay\Shared\Domain\Bus\Event;

interface GetEventBusInterface
{
    public function getEventBus(): EventBusInterface;
}
