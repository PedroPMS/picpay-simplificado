<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use App\Jobs\EventJob;
use Illuminate\Support\Facades\Queue;
use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;
use Picpay\Shared\Domain\Bus\Event\EventBusInterface;
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
        foreach ($events as $event) {
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
}
