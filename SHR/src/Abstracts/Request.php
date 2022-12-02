<?php

namespace Echosistema\SHR\Abstracts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use Psr\Http\Message\ResponseInterface;

abstract class Request
{
    protected Client $client;
    protected null|string $endpoint = null;
    protected array $response = array();
    protected array $options = array(
        'headers' => [
            'User-Agent' => 'SHR/1.0',
            'Accept' => 'application/json',
        ]
    );

    public function __construct(private readonly bool $debug = false)
    {
        $this->__init__();
    }

    private function __init__(): void
    {
        $this->client = new Client();
        $this->options['debug'] = $this->debug;
    }

    /**
     * @throws GuzzleException
     */
    public function get($endpoint, array $params = array()): array
    {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function post($endpoint, array $params = array()): array
    {
        return $this->request('POST', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function put($endpoint, array $params = array()): array
    {
        return $this->request('PUT', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function patch($endpoint, array $params = array()): array
    {
        return $this->request('PATCH', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function head($endpoint, array $params = array()): array
    {
        return $this->request('HEAD', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function delete($endpoint, array $params = array()): array
    {
        return $this->request('DELETE', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function options($endpoint, array $params = array()): array
    {
        return $this->request('OPTIONS', $endpoint, $params);
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, $endpoint, array $params = array()): array
    {
        $method = strtoupper($method);
        $this->endpoint = $endpoint;
        if (!empty($params)) {
            $this->options['form_params'] = $params;
        }
        return $this->response($this->client->request($method, $this->endpoint, $this->options))
            ->getResponse();
    }

    private function response(ResponseInterface $response): static
    {
        $response_body = $response->getBody()?->getContents() ?? null;
        $body = !$response_body ? [] : Utils::jsonDecode($response_body, true);
        $code = $response->getStatusCode();
        $this->response = [
            'code' => $code,
            'headers' => $this->parseHeaders($response->getHeaders()),
            'body' => $body,
            'fail' => $code > 300,
            'successfully' => $code < 300,
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param $responseHeaders
     * @return array
     */
    private function parseHeaders($responseHeaders): array
    {
        $headers = array();
        foreach ($responseHeaders as $header => $values) {
            $headers[$header] = implode(', ', $values);
        }
        return $headers;
    }
}