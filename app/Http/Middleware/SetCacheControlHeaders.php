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
     * @param  string  ...$cacheControlParts  Cache-Control header value parts from OpenAPI spec
     */
    public function handle(Request $request, Closure $next, string ...$cacheControlParts): Response
    {
        $response = $next($request);

        // Concatenate all parts with ", " to reconstruct the full cache control value
        // Laravel splits middleware parameters by comma, so we need to join them back
        if (!empty($cacheControlParts)) {
            // Trim each part to remove any extra spaces from the split
            $cacheControl = implode(', ', array_map('trim', $cacheControlParts));
            // Use headers->set() to properly replace any existing Cache-Control header
            $response->headers->set('Cache-Control', $cacheControl);
        }

        return $response;
    }
}
