<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GoogleSsoRelay
{
    /**
     * Recover Google auth from a short-lived, signed cookie after OAuth callback.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('google')->check()) {
            $gAuth = $request->cookies->get('g_auth');
            if (is_string($gAuth) && $gAuth !== '') {
                [$uid, $ts, $sig] = array_pad(explode('|', $gAuth, 3), 3, null);
                if ($uid && $ts && $sig) {
                    $calc = hash_hmac('sha256', $uid.'|'.$ts, config('app.key'));
                    if (hash_equals($calc, (string) $sig) && abs(time() - (int) $ts) < 300) {
                        $user = \App\Models\GoogleUser::find($uid);
                        if ($user) {
                            Auth::guard('google')->login($user, true);
                            \Log::channel('google')->info('GoogleSsoRelay: cookie re-login succeeded', [
                                'email' => $user->email,
                                'session_id' => session()->getId(),
                            ]);
                            // Invalidate relay cookie after use
                            cookie()->queue(cookie('g_auth', '', -60, '/', config('session.domain') ?: null, true, true, false, config('session.same_site') ?: 'lax'));
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}


