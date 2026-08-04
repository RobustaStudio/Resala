<?php
namespace RobustTools\Resala\Support;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class HTTP
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public static function post(string $endpoint, array $headers, $payload): ResponseInterface
    {
        return (new self())->client->request('POST', $endpoint, [
            'headers' => $headers,
            'body' => $payload
        ]);
    }

    public static function get(string $endpoint, array $headers = [], array $query = []): ResponseInterface
    {
        return (new self())->client->request('GET', $endpoint, [
            'headers' => $headers,
            'query' => $query
        ]);
    }
}
