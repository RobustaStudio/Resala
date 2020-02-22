<?php


namespace RobustTools\SMS\Support;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class HTTPClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct ()
    {
        $this->client = new Client();
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param $requestBody
     * @return SimpleXMLElement
     */
    public function post (string $endpoint, array $headers, $requestBody) : SimpleXMLElement
    {
        $response =  $this->client->request('POST', $endpoint, [
            'headers' => $headers,
            'body' => $requestBody
        ]);

        return $this->parseResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return SimpleXMLElement
     */
    private function parseResponse (ResponseInterface $response) : SimpleXMLElement
    {
        return new SimpleXMLElement($response->getBody()->getContents());
    }
}
