<?php

namespace Picpay\User\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $cpf
 * @property string $password
 * @property string $type
 */
class UserModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['id', 'name', 'email', 'cpf', 'password', 'type', 'created_at', 'updated_at'];
}
