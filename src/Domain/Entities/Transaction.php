<?php

namespace Picpay\Domain\Entities;

use JsonSerializable;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Domain\Events\Transaction\TransactionCreated;
use Picpay\Domain\Events\Transaction\TransactionInvalidated;
use Picpay\Domain\Events\Transaction\TransactionValidated;
use Picpay\Domain\Exceptions\Transaction\TransactionStatusException;
use Picpay\Domain\ValueObjects\Transaction\TransactionId;
use Picpay\Domain\ValueObjects\Transaction\TransactionValue;
use Picpay\Domain\ValueObjects\User\UserId;
use Picpay\Shared\Domain\Aggregate\AggregateRoot;

class Transaction extends AggregateRoot implements JsonSerializable
{
    public function __construct(
        public readonly TransactionId $id,
        public readonly UserId $payerId,
        public readonly UserId $payeeId,
        public readonly TransactionValue $value,
        public readonly TransactionStatus $status
    ) {
    }

    /**
     * @throws TransactionStatusException
     */
    public static function fromPrimitives(string $id, string $payerId, string $payeeId, string $value, string $status): self
    {
        return new self(
            TransactionId::fromValue($id),
            UserId::fromValue($payerId),
            UserId::fromValue($payeeId),
            TransactionValue::fromValue($value),
            TransactionStatus::fromValue($status)
        );
    }

    public static function create(TransactionId $id, UserId $payerId, UserId $payeeId, TransactionValue $value, TransactionStatus $status): self
    {
        return new self($id, $payerId, $payeeId, $value, $status);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'value' => $this->value->value(),
            'status' => $this->status,
        ];
    }

    public function transactionWasValidated(): void
    {
        $this->record(new TransactionValidated($this->id, $this->jsonSerialize()));
    }

    public function transactionWasRejected(string $message): void
    {
        $this->record(new TransactionInvalidated($this->id, $this->jsonSerialize(), $message));
    }

    public function transactionWasCreated(): void
    {
        $this->record(new TransactionCreated($this->id, $this->jsonSerialize()));
    }
}
