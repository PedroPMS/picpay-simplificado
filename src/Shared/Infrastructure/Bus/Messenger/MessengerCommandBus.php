<?php

declare(strict_types=1);

namespace Picpay\Shared\Infrastructure\Bus\Messenger;

use Picpay\Shared\Domain\Bus\Command\CommandInterface;
use Picpay\Shared\Domain\Bus\Command\CommandBusInterface;
use Picpay\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Picpay\Shared\Infrastructure\Bus\CommandNotRegisteredException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Throwable;

final class MessengerCommandBus implements CommandBusInterface
{
    private MessageBus $bus;

    public function __construct(iterable $commandHandlers)
    {
        $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(
                    new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
                ),
            ]
        );
    }

    /**
     * @throws Throwable
     * @throws CommandNotRegisteredException
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredException("Command " . get_class($command) .  " not registered.");
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }
}
