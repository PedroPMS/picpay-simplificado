<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Picpay\Shared\Domain\Bus\Event\AbstractDomainEvent;
use Picpay\Shared\Domain\Bus\Event\DomainEventSubscriberInterface;

class EventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly AbstractDomainEvent            $event,
        private readonly DomainEventSubscriberInterface $subscriber
    )
    {
    }

    public function handle(): void
    {
        $this->subscriber->__invoke($this->event);
    }
}
