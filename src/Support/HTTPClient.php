<?php
namespace RobustTools\SMS\Support;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use RobustTools\SMS\Exceptions\InternalServerErrorException;
use RobustTools\SMS\Exceptions\UnauthorizedException;
use SimpleXMLElement;

class HTTPClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param $payload
     * @return ResponseInterface|SimpleXMLElement
     * @throws InternalServerErrorException
     * @throws UnauthorizedException
     */
    public function post(string $endpoint, array $headers, $payload)
    {
        $response = $this->client->request('POST', $endpoint, [
            'headers' => $headers,
            'body' => $payload
        ]);

        if ($response->getstatusCode() == 401) {
            throw new UnauthorizedException('Unauthorized: Access is denied due to invalid credentials');
        }

        if ($response->getStatusCode() == 500) {
            throw new InternalServerErrorException("Internal Server Error");
        }

        if ($this->isXML($response->getHeader("Content-Type"))) {
            return $this->parseResponse($response);
        }

        return $response;
    }

    /**
     * Parse xml response.
     * @param ResponseInterface $response
     * @return SimpleXMLElement
     */
    private function parseResponse(ResponseInterface $response): SimpleXMLElement
    {
        return new SimpleXMLElement($response->getBody()->getContents());
    }

    /**
     * Detect response content type.
     * @param $contentType
     * @return bool
     */
    private function isXML($contentType): bool
    {
        return array_pop($contentType) == "application/xml";
    }
}
