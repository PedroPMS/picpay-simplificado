<?php

namespace Picpay\Shared\Domain\Aggregate;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

abstract class AggregateRoot
{
    private array $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(AbstractDomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
