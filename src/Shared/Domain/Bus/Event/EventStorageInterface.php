<?php

namespace Picpay\Shared\Domain\Bus\Event;

use Picpay\Shared\Domain\Entities\StoredEvent;

interface EventStorageInterface
{
    public function create(StoredEvent $storedEvent): void;
}
