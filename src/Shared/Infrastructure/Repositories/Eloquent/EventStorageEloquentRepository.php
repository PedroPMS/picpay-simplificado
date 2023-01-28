<?php

namespace Picpay\Shared\Infrastructure\Repositories\Eloquent;

use Picpay\Shared\Domain\Bus\Event\EventStorageInterface;
use Picpay\Shared\Domain\Entities\StoredEvent;
use Picpay\Shared\Infrastructure\Models\StoredEventModel;

final class EventStorageEloquentRepository implements EventStorageInterface
{
    public function __construct(private readonly StoredEventModel $model)
    {
    }

    public function create(StoredEvent $storedEvent): void
    {
        $this->model->newQuery()->create($storedEvent->jsonSerialize());
    }

    private function toDomain(StoredEventModel $storedEventModel): StoredEvent
    {
        return StoredEvent::create(
            $storedEventModel->id,
            $storedEventModel->event_name,
            $storedEventModel->event_body,
            $storedEventModel->aggregate_id,
            $storedEventModel->occurred_on,
        );
    }
}
