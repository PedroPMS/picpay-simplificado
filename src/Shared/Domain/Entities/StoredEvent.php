<?php

namespace Picpay\Shared\Domain\Entities;

use JsonSerializable;

class StoredEvent implements JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $eventName,
        public readonly string $eventBody,
        public readonly string $aggregateId,
        public readonly string $occurredOn
    ) {
    }

    public static function create(string $id, string $eventName, string $eventBody, string $aggregateId, string $occurredOn): self
    {
        return new self($id, $eventName, $eventBody, $aggregateId, $occurredOn);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'event_name' => $this->eventName,
            'event_body' => $this->eventBody,
            'aggregate_id' => $this->aggregateId,
            'occurred_on' => $this->occurredOn,
        ];
    }
}
