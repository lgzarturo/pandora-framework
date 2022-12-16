<?php

namespace Pandora\Kernel;

use Closure;
use Pandora\Server\Request;
use Pandora\Server\Response;

interface Middleware {
    public function handle(Request $request, Closure $next): Response;
}
