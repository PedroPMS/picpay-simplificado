<?php

namespace Tests\Feature\Transaction;

use App\Jobs\CommandJob;
use App\Jobs\EventJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Picpay\Domain\Enums\Transaction\TransactionStatus;
use Picpay\Infrastructure\Models\TransactionModel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCreateANewTransaction()
    {
        Queue::fake([EventJob::class]);

        /** @var TransactionModel $transaction */
        $transaction = TransactionModel::factory()->make();
        $response = $this->postJson(
            '/transaction',
            [
                'payer_id' => $transaction->payer_id,
                'payee_id' => $transaction->payee_id,
                'value' => $transaction->value,
            ]
        );

        $this->assertDatabaseHas(
            'transactions',
            [
                'payer_id' => $transaction->payer_id,
                'payee_id' => $transaction->payee_id,
                'value' => $transaction->value,
                'status' => TransactionStatus::CREATED->value,
            ]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testItDispatchATransactionCommand()
    {
        Queue::fake();
        /** @var TransactionModel $transaction */
        $transaction = TransactionModel::factory()->make();
        $response = $this->postJson(
            '/transaction',
            [
                'payer_id' => $transaction->payer_id,
                'payee_id' => $transaction->payee_id,
                'value' => $transaction->value,
            ]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        Queue::assertPushed(CommandJob::class);
    }
}
