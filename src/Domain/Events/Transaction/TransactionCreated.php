<?php

namespace Picpay\Domain\Events\Transaction;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

final class TransactionCreated extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $payerId,
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
        return new self($aggregateId, $body['payerId'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'transaction.created';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'payerId' => $this->payerId,
        ];
    }
}
