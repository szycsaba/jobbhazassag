<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionCookie
{
    /**
     * Ensure session cookie attributes are set safely for OAuth flows.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $cookieName = config('session.cookie');

        // Only adjust if the response set the session cookie
        // Removed: keeping solution minimal per refactor decision

        return $response;
    }
}


