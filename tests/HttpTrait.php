<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait HttpTrait
{
    private function upload($uri, array $files, array $headers = [], array $cookies = []): ResponseInterface
    {
        $request = new ServerRequest(
            method: 'POST',
            uri: $uri,
            headers: $headers,
            body: 'php://input'
        );

        $request = $request
            ->withCookieParams($cookies)
            ->withUploadedFiles($files);

        return $this->app->getHttp()->handle($request);
    }

    private function post($uri, array $data = [], array $headers = [], array $cookies = []): ResponseInterface
    {
        return $this->app->getHttp()->handle(
            $this->request($uri, 'POST', [], $headers, $cookies)->withParsedBody($data)
        );
    }

    private function get($uri, array $query = [], array $headers = [], array $cookies = []): ResponseInterface
    {
        return $this->app->getHttp()->handle($this->request($uri, 'GET', $query, $headers, $cookies));
    }

    private function request(
        $uri,
        string $method,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ServerRequestInterface {
        $headers = array_merge(['accept-language' => 'en'], $headers);

        $request = new ServerRequest(
            method: $method,
            uri: $uri,
            headers: $headers,
            body: 'php://input'
        );

        $request = $request
            ->withCookieParams($cookies)
            ->withQueryParams($query);

        return $request;
    }
}
