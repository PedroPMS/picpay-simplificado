<?php

namespace Picpay\Shared\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $event_name
 * @property string $event_body
 * @property string $aggregate_id
 * @property string $occurred_on
 */
class StoredEventModel extends Model
{
    use HasUuids;

    protected $table = 'events';

    protected $fillable = ['id', 'event_name', 'event_body', 'aggregate_id', 'occurred_on', 'created_at', 'updated_at'];
}
