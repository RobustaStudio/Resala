<?php
namespace RobustTools\Resala\Support;

use GuzzleHttp\Client;

final class HTTPClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function post(string $endpoint, array $headers, $payload)
    {
        return $this->client->request('POST', $endpoint, [
            'headers' => $headers,
            'body' => $payload
        ]);
    }
}
