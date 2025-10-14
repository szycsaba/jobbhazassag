<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscriber;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriberAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated with Google
        if (!Auth::guard('google')->check()) {
            // Store the intended URL for redirect after login
            $intendedUrl = $request->fullUrl();
            session()->put('url.intended', $intendedUrl);
            
            \Log::channel('google')->info('SubscriberAccess: not authenticated, redirecting to warning', [
                'intended' => $intendedUrl,
                'session_id' => session()->getId(),
                'host' => $request->getHost(),
            ]);
            return redirect()->route('subscriber.warning');
        }

        $user = Auth::guard('google')->user();
        
        $subscriber = Subscriber::where('email', $user->email)
            ->where('payment_status', 'paid')
            ->where('status', 'active')
            ->first();

        if (!$subscriber) {
            \Log::channel('google')->info('SubscriberAccess: user authenticated but no active subscription', [
                'email' => $user->email,
                'session_id' => session()->getId(),
            ]);
            return redirect()->route('subscriber.warning');
        }

        \Log::channel('google')->info('SubscriberAccess: access granted', [
            'email' => $user->email,
            'session_id' => session()->getId(),
        ]);
        return $next($request);
    }
}
