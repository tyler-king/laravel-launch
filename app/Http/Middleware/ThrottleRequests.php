<?php

namespace TKing\Launch\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  int|string  $decayMinutes
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1): Response
    {
        $key = $request->fingerprint();

        if ($this->limiter->tooManyAttempts($key, (int) $maxAttempts)) {
            throw new ThrottleRequestsException(
                'Too Many Attempts.',
                null,
                $this->getHeaders(
                    (int) $maxAttempts,
                    $this->calculateRemainingAttempts($key, (int) $maxAttempts)
                )
            );
        }

        $this->limiter->hit($key, (int) $decayMinutes * 60);

        $response = $next($request);

        foreach ($this->getHeaders((int) $maxAttempts, $this->calculateRemainingAttempts($key, (int) $maxAttempts)) as $headerKey => $value) {
            $response->headers->set($headerKey, $value);
        }

        return $response;
    }

    /**
     * Calculate the number of remaining attempts.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return int
     */
    protected function calculateRemainingAttempts($key, $maxAttempts)
    {
        return $this->limiter->remaining($key, $maxAttempts);
    }

    /**
     * Get the limit headers information.
     *
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @return array
     */
    protected function getHeaders($maxAttempts, $remainingAttempts)
    {
        return [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ];
    }
}
