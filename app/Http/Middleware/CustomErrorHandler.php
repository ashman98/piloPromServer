<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the response contains GraphQL errors
        if (isset($response->original['errors'])) {
            foreach ($response->original['errors'] as &$error) {
                $error['message'] = $this->parseErrorMessage($error);
            }
        }

        return $response;
    }

    /**
     * Parse the error message.
     *
     * @param  array  $error
     * @return string
     */
    protected function parseErrorMessage(array $error)
    {
        // Custom error parsing logic here
        if (isset($error['extensions']['category'])) {
            switch ($error['extensions']['category']) {
                case 'validation':
                    return 'Validation error: ' . $error['message'];
                case 'authorization':
                    return 'Authorization error: ' . $error['message'];
                default:
                    return 'Unknown error: ' . $error['message'];
            }
        }

        return $error['message'];
    }
}
