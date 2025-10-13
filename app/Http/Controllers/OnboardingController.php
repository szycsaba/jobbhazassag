<?php

namespace App\Http\Controllers;

use App\Models\OnsiteBlock;
use App\Models\ReflectionQuestion;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Header;
use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function index(): View
    {
        $onsiteBlocks = OnsiteBlock::with(['type.articleTypeAttributes'])
            ->orderBy('position')
            ->get();

        // Load reflection questions for self-awareness blocks
        $reflectionIds = $onsiteBlocks
            ->where('type.name', 'self-awareness')
            ->pluck('content')
            ->filter()
            ->map(function($content) {
                return is_numeric($content) ? (int)$content : null;
            })
            ->filter()
            ->unique()
            ->values();

        $reflectionQuestions = [];
        if ($reflectionIds->isNotEmpty()) {
            $reflectionQuestions = ReflectionQuestion::whereIn('reflection_id', $reflectionIds)
                ->orderBy('reflection_id')
                ->orderBy('position')
                ->get(['id', 'reflection_id', 'position', 'description'])
                ->groupBy('reflection_id');
        }

        // Load quiz questions for quiz blocks
        $quizIds = $onsiteBlocks
            ->where('type.name', 'quiz')
            ->pluck('content')
            ->filter()
            ->map(function($content) {
                return is_numeric($content) ? (int)$content : null;
            })
            ->filter()
            ->unique()
            ->values();

        $quizQuestions = [];
        $quizData = [];
        if ($quizIds->isNotEmpty()) {
            $quizQuestions = QuizQuestion::with(['options'])
                ->whereIn('quiz_id', $quizIds)
                ->orderBy('quiz_id')
                ->orderBy('position')
                ->get()
                ->groupBy('quiz_id');

            $quizData = Quiz::whereIn('id', $quizIds)
                ->pluck('title', 'id')
                ->toArray();
        }

        // Load header data for onboarding (using article_id = 0 for onboarding)
        $header = Header::where('article_id', 0)->first();

        // Load user's reason if logged in with Google
        $userReason = null;
        $user = auth()->guard('google')->user();
        $isLoggedIn = false;
        if ($user) {
            $userReason = $user->reason;
            $isLoggedIn = true;
        }

        return view('onboarding.index', compact('onsiteBlocks', 'reflectionQuestions', 'quizQuestions', 'quizData', 'header', 'userReason', 'isLoggedIn'));
    }

    public function saveReason(Request $request): JsonResponse
    {
        try {
            // Check if user is logged in with Google
            $user = auth()->guard('google')->user();
            
            // Handle status check request
            if ($request->has('check_status')) {
                $hasReason = false;
                $existingContent = '';
                
                if ($user) {
                    $existingReason = $user->reason;
                    if ($existingReason) {
                        $hasReason = true;
                        $existingContent = $existingReason->content;
                    }
                }
                
                return response()->json([
                    'is_google_user' => $user ? true : false,
                    'has_reason' => $hasReason,
                    'existing_content' => $existingContent
                ]);
            }
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Csak regisztrált tagok tölthetik ki. Kérlek regisztrálj!'
                ], 401);
            }

            $request->validate([
                'content' => 'required|string|max:2000',
            ]);

            // Check if user already has a reason
            $existingReason = Reason::where('google_user_id', $user->id)->first();
            
            if ($existingReason) {
                // Update existing reason
                $existingReason->update([
                    'content' => $request->content,
                    'updated_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Válaszod sikeresen frissítve!'
                ]);
            } else {
                // Create new reason
                Reason::create([
                    'google_user_id' => $user->id,
                    'content' => $request->content,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Válaszod sikeresen elmentve!'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a mentés során: ' . $e->getMessage()
            ], 500);
        }
    }
}
