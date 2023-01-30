<?php

namespace Picpay\Shared\Infrastructure;

use Illuminate\Support\Facades\DB;
use Picpay\Shared\Domain\DbTransactionInterface;
use Throwable;

final class EloquentDbTransaction implements DbTransactionInterface
{
    /**
     * @throws Throwable
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * @throws Throwable
     */
    public function rollBack(): void
    {
        DB::rollBack();
    }

    /**
     * @throws Throwable
     */
    public function commit(): void
    {
        DB::commit();
    }
}
