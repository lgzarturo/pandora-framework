<?php

namespace Pandora\Middlewares;

use Closure;
use JsonException;
use Pandora\Kernel\Middleware;
use Pandora\Server\Request;
use Pandora\Server\Response;

class Authentication implements Middleware {
    /**
     * @throws JsonException
     */
    final public function handle(Request $request, Closure $next): Response {
        if ($request->getContentHeader("Authorization") !== "test") {
            return Response::json(["message" => "Not authenticated"])
                ->setStatus(403);
        }
        $response = $next($request);
        $response->setHeader('X-Test-Custom-Header', 'Test Value');
        return $response;
    }
}
