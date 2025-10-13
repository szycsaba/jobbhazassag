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
            
            return redirect()->route('subscriber.warning');
        }

        $user = Auth::guard('google')->user();
        
        // Check if user has an active subscription
        $subscriber = Subscriber::where('email', $user->email)
            ->where('payment_status', 'paid')
            ->where('status', 'active')
            ->first();

        if (!$subscriber) {
            return redirect()->route('subscriber.warning');
        }

        return $next($request);
    }
}
