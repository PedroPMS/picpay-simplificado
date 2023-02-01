<?php

namespace Picpay\Domain\Services\Transaction;

interface TransactionNotifier
{
    public function sendNotification(): void;
}
