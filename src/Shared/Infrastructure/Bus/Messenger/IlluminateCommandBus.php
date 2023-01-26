<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use App\Jobs\CommandJob;
use Illuminate\Support\Facades\Queue;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Domain\Bus\Command\CommandInterface;
use Picpay\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;

final class IlluminateCommandBus implements CommandBusInterface
{
    private array $map;

    public function __construct(iterable $commandHandlers)
    {
        $this->map = CallableFirstParameterExtractor::forCallables($commandHandlers);
    }

    public function dispatch(CommandInterface $command): void
    {
        $handler = $this->map[get_class($command)][0];
        Queue::push(new CommandJob($command, $handler));
    }
}
