<?php

namespace Picpay\Domain\Events\Transaction;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

final class TransactionCreated extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly array $transactionBody,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $transactionBody, $eventId, $occurredOn);
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
        return 'transaction.created';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'transaction_body' => $this,
        ];
    }
}
