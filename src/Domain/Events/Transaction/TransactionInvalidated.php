<?php

namespace Picpay\Domain\Events\Transaction;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

final class TransactionInvalidated extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $message,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): AbstractDomainEvent {
        return new self($aggregateId, $body['message'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'transaction.invalidated';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
        ];
    }
}
