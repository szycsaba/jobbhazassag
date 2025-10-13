<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GoogleUser;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        // Clear any existing OAuth state to prevent conflicts
        session()->forget('state');
        
        // Store intended URL from parameter if provided
        if ($request->has('intended') && $request->intended) {
            session()->put('url.intended', $request->intended);
        }
        
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
            return redirect()->intended(route('onboarding'));
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Handle state mismatch - redirect to Google login again
            return redirect()->route('redirect.google')
                ->with('error', 'A bejelentkezési munkamenet lejárt. Kérjük, próbálja újra.');
                
        } catch (\Exception $e) {
            // Handle other OAuth errors
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
            
            return response()->json([
                'success' => true,
                'message' => 'Sikeresen kijelentkezett'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Google logout error', [
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
