<?php

namespace Echosistema\SHR;

use Echosistema\SHR\Abstracts\Request;

class Http extends Request
{
    public function withHeaders($headers): static
    {
        $this->options['headers'] = array_merge($this->options['headers'], $headers);
        return $this;
    }

    public function addHeader($attribute, $value): void
    {
        $this->withHeaders([$attribute => $value]);
    }

    public function withDebugger(bool $value): static
    {
        $this->options['debug'] = $value;
        return $this;
    }

    public function withErrors(bool $value = true): static
    {
        $this->options['http_errors'] = $value;
        return $this;
    }

    public function withTimeout(float $value): static
    {
        $this->options['timeout'] = $value;
        return $this;
    }

    public function withConnectTimeout(float $value): static
    {
        $this->options['connect_timeout'] = $value;
        return $this;
    }

    public function withBasicAuth($client_id, $client_secret): static
    {
        $this->options['auth'] = [$client_id, $client_secret];
        return $this;
    }

    public function withCertificate($path, $password): static
    {
        $this->options['cert'] = [$path, $password];
        return $this;
    }

    public function withBearerToken($token): static
    {
        $this->addHeader('Authorization', "Bearer $token");
        return $this;
    }

    public function withParams(array $params = array()): static
    {
        $this->options['form_params'] = $params;
        return $this;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function withQuery(array $query = array()): static
    {
        $this->options['query'] = $query;
        return $this;
    }

    /**
     * @param array $body
     * @return $this
     */
    public function withBody(array $body = array()): static
    {
        $this->options['body'] = $body;
        return $this;
    }

    /**
     * @param array $array |null $array
     * @return $this
     * Auto converts array to json
     */
    public function withJson(array $array = array()): static
    {
        $this->options['json'] = $array;
        return $this;
    }
}