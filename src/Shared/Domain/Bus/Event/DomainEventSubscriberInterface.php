<?php

namespace Picpay\Shared\Domain\Bus\Event;

interface DomainEventSubscriberInterface
{
    public static function subscribedTo(): array;
}
