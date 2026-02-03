<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleExpiredSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // If we get a 419 (CSRF token mismatch), redirect to login
        if ($response->getStatusCode() === 419) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session has expired. Please log in again.',
                    'redirect' => route('login')
                ], 419);
            }

            return redirect()->route('login')
                ->with('error', 'Your session has expired. Please log in again.');
        }

        return $response;
    }
}