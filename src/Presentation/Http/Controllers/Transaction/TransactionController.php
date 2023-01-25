<?php

namespace Picpay\Presentation\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Picpay\Application\Controllers\Transaction\Create\CreateTransactionCommand;
use Picpay\Presentation\Http\Requests\Transaction\CreateTransactionRequest;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;

class TransactionController extends Controller
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function store(CreateTransactionRequest $request): Response
    {
        $command = new CreateTransactionCommand(
            $request->input('payer_id'),
            $request->input('payee_id'),
            $request->input('value'),
        );

        $this->commandBus->dispatch($command);

        return response()->noContent();
    }

}
