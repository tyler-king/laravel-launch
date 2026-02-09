<?php

namespace TKing\Launch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheControlHeaders
{
    /**
     * Cached compiled regex patterns
     * Static to persist across requests in the same PHP process
     * Limited to prevent unbounded growth in long-running processes
     *
     * @var array
     */
    private static array $compiledPatterns = [];
    
    /**
     * Maximum number of patterns to cache
     * 
     * @var int
     */
    private const MAX_CACHE_SIZE = 100;

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

        // Get current request path with leading slash
        // Request::path() returns path without leading slash (e.g., 'api/users')
        $currentPath = '/' . $request->path();

        // Find matching rule
        foreach ($cacheControlRules as $pattern => $cacheControl) {
            // Get or compile regex pattern
            if (!isset(self::$compiledPatterns[$pattern])) {
                // Prevent unbounded cache growth
                if (count(self::$compiledPatterns) >= self::MAX_CACHE_SIZE) {
                    // Remove oldest entry (first item)
                    array_shift(self::$compiledPatterns);
                }
                self::$compiledPatterns[$pattern] = $this->convertPatternToRegex($pattern);
            }
            
            $regex = self::$compiledPatterns[$pattern];

            if (preg_match($regex, $currentPath)) {
                // Use headers->set() to properly replace any existing Cache-Control header
                $response->headers->set('Cache-Control', $cacheControl);
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
