<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GoogleUser;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        // Clear any existing OAuth state to prevent conflicts
        session()->forget('state');
        
        // Store intended URL from parameter if provided (prefer explicit param)
        if ($request->has('intended') && $request->intended) {
            session()->put('url.intended', $request->intended);
            Log::channel('google')->info('Google redirect: intended set from param', [
                'intended' => $request->intended,
                'session_id' => session()->getId(),
            ]);
        } else {
            // Fallback to existing session intended or HTTP referer
            $fallbackIntended = session('url.intended', $request->headers->get('referer'));
            if (!empty($fallbackIntended)) {
                session()->put('url.intended', $fallbackIntended);
                Log::channel('google')->info('Google redirect: intended set from fallback', [
                    'intended' => $fallbackIntended,
                    'session_id' => session()->getId(),
                ]);
            }
        }
        
        Log::channel('google')->info('Google redirect: starting OAuth');
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Only configure SSL for local environment
            if (app()->environment('local')) {
                // Create a custom Guzzle client with SSL configuration
                $caBundle = env('CURL_CA_BUNDLE');
                $client = new \GuzzleHttp\Client([
                    'verify' => $caBundle && file_exists($caBundle) ? $caBundle : false,
                    'timeout' => 30,
                    'connect_timeout' => 10,
                ]);
                
                // Set the custom client for Socialite
                Socialite::driver('google')->setHttpClient($client);
            }
            
            $user = Socialite::driver('google')->user();
            $findUser = GoogleUser::where('google_id', $user->id)->first();

            if(!is_null($findUser)) {
                Auth::guard('google')->login($findUser, true); // true = remember me
            } else {
                $newUser = GoogleUser::create([
                    'google_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => now(),
                    'avatar_url' => $user->avatar,
                ]);

                Auth::guard('google')->login($newUser, true); // true = remember me
            }

            // Use Laravel's built-in intended redirect functionality
            // This will automatically handle the 'url.intended' session key
            Log::channel('google')->info('Google callback: login success', [
                'google_id' => isset($findUser) && $findUser ? $findUser->google_id : (isset($newUser) ? $newUser->google_id : null),
                'email' => $findUser->email ?? ($newUser->email ?? null),
                'intended' => session('url.intended'),
                'session_id' => session()->getId(),
            ]);
            // Regenerate session ID to avoid fixation and ensure a fresh cookie is set
            $request->session()->migrate(true);
            // Explicitly set remember to persist login across session cookies
            Auth::guard('google')->login(Auth::guard('google')->user(), true);
            // Log the new session id after migrate for correlation
            Log::channel('google')->info('Google callback: session migrated', [
                'session_id' => session()->getId(),
            ]);
            // Create a signed auth cookie to recover auth across session anomalies
            $uid = Auth::guard('google')->id();
            $ts = time();
            $sig = hash_hmac('sha256', $uid.'|'.$ts, config('app.key'));
            $cookieValue = $uid.'|'.$ts.'|'.$sig;
            $minutes = 60 * 24 * 30; // 30 days
            $cookie = cookie(
                'g_auth',
                $cookieValue,
                $minutes,
                '/',
                config('session.domain') ?: null,
                true,
                true,
                false,
                config('session.same_site') ?: 'lax'
            );

            $intended = session('url.intended', route('onboarding'));
            return redirect()->to($intended)->withCookie($cookie);
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Handle state mismatch - redirect to Google login again
            // Preserve intended URL across retry if available
            $intended = session('url.intended', $request->headers->get('referer'));
            Log::channel('google')->warning('Google callback: invalid state, retrying', [
                'error' => $e->getMessage(),
                'intended' => $intended,
                'session_id' => session()->getId(),
            ]);
            return redirect()->route('redirect.google', [
                'intended' => $intended,
            ])->with('error', 'A bejelentkezési munkamenet lejárt. Kérjük, próbálja újra.');
                
        } catch (\Exception $e) {
            // Handle other OAuth errors
            Log::channel('google')->error('Google OAuth error', [
                'error' => $e->getMessage(),
                'session_id' => session()->getId(),
                'intended' => session('url.intended'),
            ]);
            return redirect()->route('home')
                ->with('error', 'Hiba történt a bejelentkezés során. Kérjük, próbálja újra.');
        }
    }

    public function logout()
    {
        try {
            // Log the user out from the Google guard
            Auth::guard('google')->logout();
            
            // Clear all session data
            session()->flush();
            
            Log::channel('google')->info('Google logout success');
            return response()->json([
                'success' => true,
                'message' => 'Sikeresen kijelentkezett'
            ]);
            
        } catch (\Exception $e) {
            Log::channel('google')->error('Google logout error', [
                'error' => $e->getMessage(),
                'session_id' => session()->getId(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kijelentkezés során'
            ], 500);
        }
    }
}
