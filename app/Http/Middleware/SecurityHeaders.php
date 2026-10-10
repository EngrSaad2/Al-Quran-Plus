<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach recommended production security & SEO headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Suppress PHP version exposure
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        $response = $next($request);

        // Remove X-Powered-By from Laravel response
        $response->headers->remove('X-Powered-By');

        // HSTS (HTTP Strict Transport Security)
        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload', false);
        } else {
            // For production environments behind Cloudflare/reverse proxy
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload', false);
        }

        // Permissions-Policy (Feature Policy)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()', false);

        // Core Protection Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        $response->headers->set('X-XSS-Protection', '1; mode=block', false);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);

        return $response;
    }
}
