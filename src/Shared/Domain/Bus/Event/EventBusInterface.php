<?php

namespace Picpay\Shared\Domain\Bus\Event;

interface EventBusInterface
{
    public function publish(AbstractDomainEvent ...$events): void;
}
