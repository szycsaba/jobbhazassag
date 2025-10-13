<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Article;
use App\Models\ReflectionQuestion;
use App\Rules\NormalizeWhitespace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class DashboardReflectionController extends Controller
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
            $reflections = Reflection::with('article')
                ->join('articles', 'reflections.article_id', '=', 'articles.id')
                ->select('reflections.*')
                ->orderBy('articles.title', $sortDirection)
                ->get()
                ->map(function ($reflection) {
                    $reflection->short_title = Str::limit($reflection->title, 40);
                    return $reflection;
                });
        } else {
            $reflections = Reflection::with('article')
                ->orderBy($sortBy, $sortDirection)
                ->get()
                ->map(function ($reflection) {
                    $reflection->short_title = Str::limit($reflection->title, 40);
                    return $reflection;
                });
        }

        return view('dashboard-reflections', compact('reflections', 'sortBy', 'sortDirection'));
    }

    public function createReflection(): View
    {
        $articles = Article::orderBy('title')->get(['id', 'title']);
        return view('dashboard-reflection-create', compact('articles'));
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

            $reflection = Reflection::create([
                'title' => $validated['title'],
                'article_id' => $validated['article_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Önismeret sikeresen létrehozva.',
                'redirect' => route('dashboard-reflections'),
                'id' => $reflection->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az önismeret létrehozása során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyReflection(int $id): JsonResponse
    {
        try {
            $reflection = Reflection::findOrFail($id);
            $reflection->delete(); // cascades via FKs on reflection_questions and user_reflection_notes

            return response()->json([
                'success' => true,
                'message' => 'Önismeret és kapcsolódó adatai törölve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a törlés során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): View
    {
        $reflection = Reflection::with(['questions' => function ($query) {
            $query->orderBy('position');
        }])->findOrFail($id);

        return view('dashboard-reflection-show', compact('reflection'));
    }

    public function create(int $id): View
    {
        $reflection = Reflection::findOrFail($id);
        return view('dashboard-reflection-add', compact('reflection'));
    }

    public function storeQuestion(Request $request, int $id): JsonResponse
    {
        try {
            $reflection = Reflection::findOrFail($id);

            $validated = $request->validate([
                'description' => ['required', 'string'],
            ]);

            // Normalize whitespace
            $validated['description'] = NormalizeWhitespace::normalize($validated['description']);

            // Next position
            $nextPosition = $reflection->questions()->max('position') + 1;

            $question = $reflection->questions()->create([
                'description' => $validated['description'],
                'position' => $nextPosition,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kérdés sikeresen létrehozva.',
                'redirect' => route('dashboard-reflections.show', ['id' => $reflection->id]),
                'id' => $question->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kérdés létrehozása során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reorder(Request $request, int $id): JsonResponse
    {
        try {
            $reflection = Reflection::findOrFail($id);

            $validated = $request->validate([
                'positions' => 'required|array',
                'positions.*.id' => 'required|integer|exists:reflection_questions,id',
                'positions.*.position' => 'required|integer|min:1',
            ]);

            // Update positions for each question
            foreach ($validated['positions'] as $positionData) {
                $question = $reflection->questions()
                    ->where('id', $positionData['id'])
                    ->first();

                if ($question) {
                    $question->update(['position' => $positionData['position']]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Kérdések sikeresen újrarendezve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az újrarendezés során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a reflection question
     */
    public function destroyQuestion(int $id, int $question_id): JsonResponse
    {
        try {
            $reflection = Reflection::findOrFail($id);

            $question = $reflection->questions()
                ->where('id', $question_id)
                ->firstOrFail();

            // Delete the question (this will cascade delete all user reflection notes due to foreign key constraint)
            $question->delete();

            // Reorder all remaining questions starting from position 1
            $this->reorderQuestions($reflection->id);

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
     * Show the form for editing a reflection question
     */
    public function edit(int $id, int $question_id): View
    {
        $reflection = Reflection::findOrFail($id);
        $question = $reflection->questions()->findOrFail($question_id);
        
        return view('dashboard-reflection-edit', compact('reflection', 'question'));
    }

    /**
     * Update a reflection question
     */
    public function update(Request $request, int $id, int $question_id): JsonResponse
    {
        try {
            $reflection = Reflection::findOrFail($id);
            $question = $reflection->questions()->findOrFail($question_id);

            $validated = $request->validate([
                'description' => ['required', 'string'],
            ]);

            // Normalize whitespace
            $validated['description'] = NormalizeWhitespace::normalize($validated['description']);

            $question->update([
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kérdés sikeresen frissítve.',
                'redirect' => route('dashboard-reflections.show', ['id' => $reflection->id]),
                'id' => $question->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kérdés frissítése során: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reorder questions after deletion
     */
    private function reorderQuestions(int $reflectionId): void
    {
        $questions = ReflectionQuestion::where('reflection_id', $reflectionId)
            ->orderBy('position')
            ->get();

        foreach ($questions as $index => $question) {
            $question->position = $index + 1;
            $question->save();
        }
    }
}


