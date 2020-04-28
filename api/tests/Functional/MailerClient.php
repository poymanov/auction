<?php

declare(strict_types=1);

namespace Test\Functional;

use GuzzleHttp\Client;
use JsonException;

class MailerClient
{
    /**
     * @var Client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://mailer:8025'
        ]);
    }

    public function clear(): void
    {
        $this->client->delete('/api/v1/messages');
    }

    /**
     * @param string $to
     * @return bool
     * @throws JsonException
     */
    public function hasEmailSentTo(string $to): bool
    {
        $response = $this->client->get('/api/v2/search?kind=to&query=' . urlencode($to));

        /** @psalm-var array{total:int} $data */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        return $data['total'] > 0;
    }
}
