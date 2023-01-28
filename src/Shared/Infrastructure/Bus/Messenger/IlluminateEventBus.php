<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use App\Jobs\EventJob;
use Illuminate\Support\Facades\Queue;
use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
use Picpay\Shared\Domain\Bus\Event\EventStorageInterface;
use Picpay\Shared\Domain\Entities\StoredEvent;
use Picpay\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;

final class IlluminateEventBus implements EventBusInterface
{
    private array $map;

    public function __construct(iterable $subscribers)
    {
        $this->map = CallableFirstParameterExtractor::forPipedCallables($subscribers);
    }

    public function publish(AbstractDomainEvent ...$events): void
    {
        $eventStorage = app(EventStorageInterface::class);
        foreach ($events as $event) {
            $this->storeEvent($eventStorage, $event);
            $subscribers = $this->map[get_class($event)];
            $this->sendToSubscribers($event, $subscribers);
        }
    }

    private function sendToSubscribers(AbstractDomainEvent $event, array $subscribers)
    {
        foreach ($subscribers as $subscriber) {
            Queue::push(new EventJob($event, $subscriber));
        }
    }

    private function storeEvent(EventStorageInterface $eventStorage, AbstractDomainEvent $event)
    {
        $storedEvent = StoredEvent::create(
            $event->eventId(),
            get_class($event),
            json_encode($event->aggregateBody()),
            $event->aggregateId(),
            $event->occurredOn(),
        );

        $eventStorage->create($storedEvent);
    }
}
