<?php

namespace Picpay\Domain\Services\Transaction;

interface TransactionAuthorizer
{
    public function isAutorized(): bool;
}
