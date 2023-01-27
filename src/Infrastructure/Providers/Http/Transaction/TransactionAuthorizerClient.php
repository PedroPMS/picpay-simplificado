<?php

namespace Picpay\Infrastructure\Providers\Http\Transaction;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Picpay\Domain\Services\Transaction\TransactionAuthorizer;

class TransactionAuthorizerClient implements TransactionAuthorizer
{
    public const API_VERSION = 'v3';

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @throws GuzzleException
     */
    private function send(string $url)
    {
        $response = $this->client->get($url);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function isAutorized(): bool
    {
        $message = $this->send(config('services.transaction_autorizer.autorizer_url'))['message'];

        return $message === config('services.transaction_autorizer.autorized_message');
    }
}
