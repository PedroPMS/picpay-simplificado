<?php

namespace Tests\Feature\Transaction;

use App\Jobs\EventJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Picpay\Application\Controllers\Transaction\Debit\DebitTransactionCommand;
use Picpay\Infrastructure\Models\TransactionModel;
use Picpay\Infrastructure\Models\UserModel;
use Picpay\Infrastructure\Models\WalletModel;
use Picpay\Shared\Domain\Bus\Event\GetCommandBusInterface;
use Tests\TestCase;

class DebitTest extends TestCase
{
    use DatabaseTransactions;

    public function testItDebitTransactionValueFromWallet()
    {
        Queue::fake([EventJob::class]);

        /** @var GetCommandBusInterface $transaction */
        $bus = app(GetCommandBusInterface::class);

        $user = UserModel::factory()->has(WalletModel::factory(), 'wallet')->create();
        /** @var TransactionModel $transaction */
        $transaction = TransactionModel::factory(['payer_id' => $user->id])->create();

        $expectedAmount = $user->wallet->amount - $transaction->value;

        $command = new DebitTransactionCommand($transaction->id);
        $bus->getCommandBus()->dispatch($command);

        $this->assertDatabaseHas(
            'wallet',
            [
                'user_id' => $transaction->payer_id,
                'amount' => $expectedAmount,
            ]
        );
    }
}
