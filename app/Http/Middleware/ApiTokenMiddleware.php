<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for the presence of the X-API-TOKEN header
        $apiToken = $request->header('X-API-TOKEN');

        // Check if the token matches the configured token
        if ($apiToken !== config('app.api_token')) {
            return response()->json([
                'message' => 'Unauthorized. Invalid API token.',
            ], 401);
        }

        return $next($request);
    }
}
