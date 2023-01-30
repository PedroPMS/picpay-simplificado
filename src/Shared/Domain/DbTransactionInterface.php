<?php

namespace Picpay\Shared\Domain;

interface DbTransactionInterface
{
    public function beginTransaction(): void;

    public function rollBack(): void;

    public function commit(): void;
}
