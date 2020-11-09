<?php

declare(strict_types=1);

namespace Spiral\Keeper\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Http\Exception\ClientException\ForbiddenException;
use Spiral\Security\GuardInterface;
use Spiral\WriteAway\Config\WriteAwayConfig;

class AccessMiddleware implements MiddlewareInterface
{
    private WriteAwayConfig $config;
    private GuardInterface $guard;

    public function __construct(WriteAwayConfig $config, GuardInterface $guard)
    {
        $this->config = $config;
        $this->guard = $guard;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->guard->allows($this->config->editPermission())) {
            throw new ForbiddenException();
        }

        return $handler->handle($request);
    }
}
