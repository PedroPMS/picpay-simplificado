<?php

namespace Picpay\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $payer_id
 * @property string $payee_id
 * @property int $value
 * @property string $status
 */
class TransactionModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = ['id', 'payer_id', 'payee_id', 'value', 'status', 'created_at', 'updated_at'];
}
