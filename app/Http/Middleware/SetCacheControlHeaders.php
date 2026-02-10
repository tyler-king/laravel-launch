<?php

namespace TKing\Launch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheControlHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $cacheControl  Cache-Control header value from OpenAPI spec
     */
    public function handle(Request $request, Closure $next, ?string $cacheControl = null): Response
    {
        $response = $next($request);

        // Use the cache control value passed as parameter (from OpenAPI spec)
        if (!empty($cacheControl)) {
            // Use headers->set() to properly replace any existing Cache-Control header
            $response->headers->set('Cache-Control', $cacheControl);
        }

        return $response;
    }
}
