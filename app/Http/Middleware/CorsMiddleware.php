<?php
//this file sus...
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Add CORS headers for all requests
        $headers = [
            'Access-Control-Allow-Origin'      => 'http://localhost:5173', // Be specific, not '*'
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', // Include OPTIONS
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization', // Match your frontend's headers
            'Access-Control-Allow-Credentials' => 'true', // Set this if your frontend sends cookies/Auth tokens
            'Access-Control-Max-Age'           => '86400', // Cache preflight for 24 hours (optional)
        ];

        // Handle preflight OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 204) // Respond with 204 No Content for preflight
                             ->withHeaders($headers); // Attach headers
        }

        // For actual requests (GET, POST, PATCH, etc.)
        $response = $next($request);

        // Add CORS headers to the response of the actual request
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
