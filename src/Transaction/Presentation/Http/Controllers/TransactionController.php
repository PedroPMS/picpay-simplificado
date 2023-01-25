<?php

namespace Picpay\Transaction\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Transaction\Application\Create\CreateTransactionCommand;
use Picpay\Transaction\Presentation\Http\Requests\CreateTransactionRequest;

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
