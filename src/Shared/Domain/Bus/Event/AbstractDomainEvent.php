<?php

namespace Picpay\Shared\Domain\Bus\Event;

use DateTimeImmutable;
use Picpay\Shared\Domain\ValueObject\UuidValueObject;

abstract class AbstractDomainEvent
{
    private string $aggregateId;

    private string $eventId;

    private string $occurredOn;

    private array $aggregateBody;

    public function __construct(string $aggregateId, array $aggregateBody = [], string $eventId = null, string $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: UuidValueObject::random()->value;
        $this->occurredOn = $occurredOn ?: (new DateTimeImmutable())->format('Y-m-d H:i:s.u T');
        $this->aggregateBody = $aggregateBody;
    }

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function aggregateBody(): array
    {
        return $this->aggregateBody;
    }
}
