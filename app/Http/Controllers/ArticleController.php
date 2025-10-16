<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Header;
use App\Models\ReflectionQuestion;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\UserReflectionNotes;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show($slug): View
    {
        $article = Article::with(['articleBlocks.type.articleTypeAttributes'])->where('slug', $slug)->firstOrFail();

        // Load reflection questions for self-awareness blocks
        $reflectionIds = $article->articleBlocks()
            ->whereHas('type', function($query) {
                $query->where('name', 'self-awareness');
            })
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
        $quizIds = $article->articleBlocks()
            ->whereHas('type', function($query) {
                $query->where('name', 'quiz');
            })
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
            $quizQuestions = QuizQuestion::with(['options' => function($query) {
                $query->orderBy('position');
            }])
                ->whereIn('quiz_id', $quizIds)
                ->orderBy('quiz_id')
                ->orderBy('position')
                ->get()
                ->groupBy('quiz_id');
            
            // Get quiz titles for each quiz
            $quizzes = Quiz::whereIn('id', $quizIds)->pluck('title', 'id');
            $quizData = $quizzes->toArray();
        }

        // Load header data for this article
        $header = Header::where('article_id', $article->id)->first();

        // Load user's reflection notes if logged in with Google
        $userReflectionNotes = [];
        $user = auth()->guard('google')->user();
        if ($user && $reflectionIds->isNotEmpty()) {
            // Get all reflection question IDs for these reflections
            $reflectionQuestionIds = ReflectionQuestion::whereIn('reflection_id', $reflectionIds)
                ->pluck('id')
                ->toArray();
                
            $userReflectionNotes = UserReflectionNotes::where('google_user_id', $user->id)
                ->whereIn('reflection_question_id', $reflectionQuestionIds)
                ->pluck('note_text', 'reflection_question_id')
                ->toArray();
        }

        // Load previous lessons (onboarding + previous articles)
        $previousLessons = collect();
        
        // Always add onboarding first
        $previousLessons->push((object)[
            'title' => 'Bevezetés',
            'slug' => 'onboarding',
            'url' => route('onboarding'),
            'is_onboarding' => true
        ]);
        
        // Add previous articles (exclude current article)
        // Get current article's created_at for comparison
        $currentArticleCreatedAt = $article->created_at;
        
        $previousArticles = Article::where('created_at', '<', $currentArticleCreatedAt)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'title', 'slug', 'created_at'])
            ->map(function($article) {
                return (object)[
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'url' => route('article.show', $article->slug),
                    'is_onboarding' => false,
                    'created_at' => $article->created_at
                ];
            });
        
        $previousLessons = $previousLessons->merge($previousArticles);

        return view('articles.index', compact('article', 'header', 'reflectionQuestions', 'quizQuestions', 'quizData', 'userReflectionNotes', 'previousLessons'));
    }

    public function saveReflectionNotes(Request $request, $slug): JsonResponse
    {
        try {
            // Check if user is logged in with Google
            $user = auth()->guard('google')->user();
            
            // Handle status check request
            if ($request->has('check_status')) {
                $hasNotes = false;
                $existingNotes = [];
                
                if ($user) {
                    // Get the article
                    $article = Article::where('slug', $slug)->firstOrFail();
                    
                    // Get reflection IDs for this article
                    $reflectionIds = $article->articleBlocks()
                        ->whereHas('type', function($query) {
                            $query->where('name', 'self-awareness');
                        })
                        ->pluck('content')
                        ->filter()
                        ->map(function($content) {
                            return is_numeric($content) ? (int)$content : null;
                        })
                        ->filter()
                        ->unique()
                        ->values();
                    
                    if ($reflectionIds->isNotEmpty()) {
                        // Get all reflection question IDs for these reflections
                        $reflectionQuestionIds = ReflectionQuestion::whereIn('reflection_id', $reflectionIds)
                            ->pluck('id')
                            ->toArray();
                        
                        $userNotes = UserReflectionNotes::where('google_user_id', $user->id)
                            ->whereIn('reflection_question_id', $reflectionQuestionIds)
                            ->pluck('note_text', 'reflection_question_id')
                            ->toArray();
                        
                        $hasNotes = !empty($userNotes);
                        $existingNotes = $userNotes;
                    }
                }
                
                return response()->json([
                    'is_google_user' => $user ? true : false,
                    'has_notes' => $hasNotes,
                    'existing_notes' => $existingNotes
                ]);
            }
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Csak regisztrált tagok tölthetik ki. Kérlek regisztrálj!'
                ], 401);
            }

            $request->validate([
                'notes' => 'required|array',
                'notes.*' => 'nullable|string|max:2000',
            ]);

            // Get the article
            $article = Article::where('slug', $slug)->firstOrFail();
            
            // Get reflection questions for this article
            $reflectionIds = $article->articleBlocks()
                ->whereHas('type', function($query) {
                    $query->where('name', 'self-awareness');
                })
                ->pluck('content')
                ->filter()
                ->map(function($content) {
                    return is_numeric($content) ? (int)$content : null;
                })
                ->filter()
                ->unique()
                ->values();

            if ($reflectionIds->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nincs önismereti kérdés ehhez a cikkhez.'
                ], 400);
            }

            // Get all reflection question IDs for these reflections
            $reflectionQuestionIds = ReflectionQuestion::whereIn('reflection_id', $reflectionIds)
                ->pluck('id')
                ->toArray();

            // Handle saving/updating reflection notes
            $savedCount = 0;
            $updatedCount = 0;
            
            foreach ($request->notes as $questionId => $content) {
                if (in_array($questionId, $reflectionQuestionIds)) {
                    $trimmedContent = trim($content);
                    
                    // Check if user already has a note for this question
                    $existingNote = UserReflectionNotes::where('google_user_id', $user->id)
                        ->where('reflection_question_id', $questionId)
                        ->first();
                    
                    if ($existingNote) {
                        // Update existing note
                        $existingNote->update([
                            'note_text' => $trimmedContent ?: null,
                            'updated_at' => now(),
                        ]);
                        $updatedCount++;
                    } else {
                        // Create new note (only if content is not empty)
                        if (!empty($trimmedContent)) {
                            UserReflectionNotes::create([
                                'google_user_id' => $user->id,
                                'reflection_question_id' => $questionId,
                                'note_text' => $trimmedContent,
                            ]);
                            $savedCount++;
                        }
                    }
                }
            }

            // Determine appropriate success message
            if ($updatedCount > 0 && $savedCount > 0) {
                $message = 'Válaszaid sikeresen mentve és frissítve!';
            } elseif ($updatedCount > 0) {
                $message = 'Válaszaid sikeresen frissítve!';
            } elseif ($savedCount > 0) {
                $message = 'Válaszaid sikeresen elmentve!';
            } else {
                $message = 'Nincs új válasz a mentéshez.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a mentés során: ' . $e->getMessage()
            ], 500);
        }
    }
}
