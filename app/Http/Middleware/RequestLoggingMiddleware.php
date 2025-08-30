<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class RequestLoggingMiddleware
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, $handler): ResponseInterface
    {
        $method = $request->getMethod();
        $path   = $request->getUri()->getPath();

        // If authentication middleware sets userId attribute
        $userId = $request->getAttribute('userId', null);

        $this->logger->info(json_encode([
            'method' => $method,
            'path'   => $path,
            'userId' => $userId,
            'time'   => date('c')
        ]));

        return $handler->handle($request);
    }
}
