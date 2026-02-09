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
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get cache control configuration
        $cacheControlRules = config('launch.cache_control', []);

        if (empty($cacheControlRules)) {
            return $response;
        }

        // Get current request path
        $currentPath = $request->path();

        // Find matching rule
        foreach ($cacheControlRules as $pattern => $cacheControl) {
            // Convert pattern to regex
            $regex = $this->convertPatternToRegex($pattern);

            if (preg_match($regex, '/' . $currentPath)) {
                $response->header('Cache-Control', $cacheControl);
                break;
            }
        }

        return $response;
    }

    /**
     * Convert route pattern to regex
     *
     * @param string $pattern
     * @return string
     */
    private function convertPatternToRegex(string $pattern): string
    {
        // Escape special regex characters except *
        $pattern = preg_quote($pattern, '/');
        
        // Convert * to match any characters
        $pattern = str_replace('\*', '.*', $pattern);
        
        // Ensure pattern matches from start to end
        return '/^' . $pattern . '$/';
    }
}
