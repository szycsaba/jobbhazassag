<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Article;
use App\Rules\NormalizeWhitespace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class DashboardQuizController extends Controller
{
    public function list(Request $request): View
    {
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['title', 'article_title', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Handle article_title sorting through join
        if ($sortBy === 'article_title') {
            $quizzes = Quiz::with('article')
                ->join('articles', 'quizzes.article_id', '=', 'articles.id')
                ->select('quizzes.*')
                ->orderBy('articles.title', $sortDirection)
                ->get()
                ->map(function ($quiz) {
                    $quiz->short_title = Str::limit($quiz->title, 40);
                    return $quiz;
                });
        } else {
            $quizzes = Quiz::with('article')
                ->orderBy($sortBy, $sortDirection)
                ->get()
                ->map(function ($quiz) {
                    $quiz->short_title = Str::limit($quiz->title, 40);
                    return $quiz;
                });
        }

        return view('dashboard-quiz', compact('quizzes', 'sortBy', 'sortDirection'));
    }

    public function show(int $id): View
    {
        $quiz = Quiz::with(['questions' => function ($query) {
            $query->orderBy('position');
        }, 'questions.options' => function ($query) {
            $query->orderBy('position');
        }])->findOrFail($id);

        return view('dashboard-quiz-show', compact('quiz'));
    }

    public function edit(int $id): View
    {
        $quiz = Quiz::findOrFail($id);
        $articles = Article::orderBy('title')->get(['id', 'title']);
        
        return view('dashboard-quiz-edit', compact('quiz', 'articles'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);

            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'article_id' => ['required', 'exists:articles,id'],
            ]);

            // Normalize whitespace in title to mirror article flow
            $validated['title'] = NormalizeWhitespace::normalize($validated['title']);

            $quiz->update([
                'title' => $validated['title'],
                'article_id' => $validated['article_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kvíz sikeresen frissítve.',
                'redirect' => route('dashboard-quiz.show', $quiz->id),
                'id' => $quiz->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kvíz frissítése során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createQuiz(): View
    {
        $articles = Article::orderBy('title')->get(['id', 'title']);
        return view('dashboard-quiz-create', compact('articles'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'article_id' => ['required', 'exists:articles,id'],
            ]);

            // Normalize whitespace in title to mirror article flow
            $validated['title'] = NormalizeWhitespace::normalize($validated['title']);

            $quiz = Quiz::create([
                'title' => $validated['title'],
                'article_id' => $validated['article_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kvíz sikeresen létrehozva.',
                'redirect' => route('dashboard-quiz'),
                'id' => $quiz->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kvíz létrehozása során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createQuestion(int $id): View
    {
        $quiz = Quiz::findOrFail($id);
        return view('dashboard-quiz-question-add', compact('quiz'));
    }

    public function storeQuestion(Request $request, int $id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);

            $validated = $request->validate([
                'question_text' => ['required', 'string'],
                'explanation' => ['nullable', 'string'],
                'options' => ['required', 'array', 'min:2'],
                'options.*.option_text' => ['required', 'string'],
                'options.*.is_correct' => ['required', 'string', 'in:true,false'],
                'correct_option' => ['required', 'integer', 'min:0'],
            ]);

            // Normalize whitespace
            $validated['question_text'] = NormalizeWhitespace::normalize($validated['question_text']);
            if ($validated['explanation']) {
                $validated['explanation'] = NormalizeWhitespace::normalize($validated['explanation']);
            }

            // Next position
            $nextPosition = $quiz->questions()->max('position') + 1;

            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
                'explanation' => $validated['explanation'],
                'position' => $nextPosition,
            ]);

            // Create options
            foreach ($validated['options'] as $index => $optionData) {
                $question->options()->create([
                    'option_text' => NormalizeWhitespace::normalize($optionData['option_text']),
                    'is_correct' => $optionData['is_correct'] === 'true',
                    'position' => $index + 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kérdés sikeresen létrehozva.',
                'redirect' => route('dashboard-quiz.show', ['id' => $quiz->id]),
                'id' => $question->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kérdés létrehozása során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editQuestion(int $id, int $question_id): View
    {
        $quiz = Quiz::findOrFail($id);
        $question = $quiz->questions()->with('options')->findOrFail($question_id);
        
        return view('dashboard-quiz-question-edit', compact('quiz', 'question'));
    }

    public function updateQuestion(Request $request, int $id, int $question_id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $question = $quiz->questions()->findOrFail($question_id);

            $validated = $request->validate([
                'question_text' => ['required', 'string'],
                'explanation' => ['nullable', 'string'],
                'options' => ['required', 'array', 'min:2'],
                'options.*.option_text' => ['required', 'string'],
                'options.*.is_correct' => ['required', 'string', 'in:true,false'],
                'correct_option' => ['required', 'integer', 'min:0'],
            ]);

            // Normalize whitespace
            $validated['question_text'] = NormalizeWhitespace::normalize($validated['question_text']);
            if ($validated['explanation']) {
                $validated['explanation'] = NormalizeWhitespace::normalize($validated['explanation']);
            }

            $question->update([
                'question_text' => $validated['question_text'],
                'explanation' => $validated['explanation'],
            ]);

            // Delete existing options and create new ones
            $question->options()->delete();

            foreach ($validated['options'] as $index => $optionData) {
                $question->options()->create([
                    'option_text' => NormalizeWhitespace::normalize($optionData['option_text']),
                    'is_correct' => $optionData['is_correct'] === 'true',
                    'position' => $index + 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kérdés sikeresen frissítve.',
                'redirect' => route('dashboard-quiz.show', ['id' => $quiz->id]),
                'id' => $question->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kérdés frissítése során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyQuestion(int $id, int $question_id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);

            $question = $quiz->questions()
                ->where('id', $question_id)
                ->firstOrFail();

            // Delete the question (this will cascade delete all options due to foreign key constraint)
            $question->delete();

            // Reorder all remaining questions starting from position 1
            $this->reorderQuestions($quiz->id);

            return response()->json([
                'success' => true,
                'message' => 'Kérdés sikeresen törölve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kérdés törlése során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder questions after deletion
     */
    private function reorderQuestions(int $quizId): void
    {
        $questions = QuizQuestion::where('quiz_id', $quizId)
            ->orderBy('position')
            ->get();

        foreach ($questions as $index => $question) {
            $question->position = $index + 1;
            $question->save();
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->delete(); // cascades via FKs on quiz_questions and quiz_options

            return response()->json([
                'success' => true,
                'message' => 'Kvíz és kapcsolódó adatai törölve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a törlés során: ' . $e->getMessage()
            ], 500);
        }
    }
}
