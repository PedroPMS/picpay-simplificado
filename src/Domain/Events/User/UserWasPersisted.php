<?php

namespace Picpay\Domain\Events\User;

use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;

final class UserWasPersisted extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly array $userBody,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $userBody, $eventId, $occurredOn);
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
        return 'user.was_persisted';
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'user_body' => $this,
        ];
    }
}
