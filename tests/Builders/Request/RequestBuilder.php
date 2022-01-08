<?php

namespace Tests\Builders\Request;

use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

class RequestBuilder
{
    private Request $request;
    private Uri $uri;
    private array $headers = [
        'HTTP_ACCEPT' => 'application/json',
        'Content-Type' => 'application/json',
    ];
    private array $serverParams = [];
    private array $cookies = [];
    private StreamInterface $stream;

    public function __construct(private string $method, private string $path)
    {
        $this->uri = new Uri('', '', 80, $this->path);
        $handle = fopen('php://temp', 'w+');
        $this->stream = (new StreamFactory())->createStreamFromResource($handle);
    }

    public function build()
    {
        $headers = $this->getHeaders();

        $this->request = new Request(
            $this->method,
            $this->uri,
            $headers,
            $this->cookies,
            $this->serverParams,
            $this->stream
        );

        return $this->request;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function withCookie(string $value): self
    {
        $this->cookies[] = $value;

        return $this;
    }

    public function withCookies(array $cookies): self
    {
        $this->cookies = array_merge($this->cookies, $cookies);

        return $this;
    }

    public function withServerParams(array $serverParams): self
    {
        $this->serverParams = array_merge($this->serverParams, $serverParams);

        return $this;
    }

    public function withServerParam($serverParam): self
    {
        $this->serverParams[] = $serverParam;

        return $this;
    }

    private function getHeaders(): Headers
    {
        $h = new Headers();
        foreach ($this->headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return $h;
    }
}
