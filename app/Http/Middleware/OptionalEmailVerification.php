<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If BYPASS_EMAIL is enabled, skip email verification
        if (config('app.bypass_email', false)) {
            return $next($request);
        }

        // Otherwise, enforce email verification like Laravel's built-in verified middleware
        if (!$request->user() ||
            ($request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
             !$request->user()->hasVerifiedEmail())) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your email address is not verified.'], 403);
            }

            return redirect()->guest(route('verification.notice'));
        }

        return $next($request);
    }
}
