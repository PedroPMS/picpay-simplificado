<?php

namespace Picpay\Domain\Events\Transaction;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

final class TransactionDebited extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly array $aggregateBody,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $aggregateBody, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): AbstractDomainEvent {
        return new self($aggregateId, $body, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'transaction.debited';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'aggregateBody' => $this->aggregateBody,
        ];
    }
}
