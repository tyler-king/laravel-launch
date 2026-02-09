<?php

namespace TKing\Launch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheControlHeaders
{
    /**
     * Cached compiled regex patterns
     *
     * @var array
     */
    private array $compiledPatterns = [];

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

        // Get current request path (without leading slash)
        $currentPath = '/' . $request->path();

        // Find matching rule
        foreach ($cacheControlRules as $pattern => $cacheControl) {
            // Get or compile regex pattern
            if (!isset($this->compiledPatterns[$pattern])) {
                $this->compiledPatterns[$pattern] = $this->convertPatternToRegex($pattern);
            }
            
            $regex = $this->compiledPatterns[$pattern];

            if (preg_match($regex, $currentPath)) {
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
