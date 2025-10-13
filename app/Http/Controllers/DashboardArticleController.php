<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleBlock;
use App\Models\ArticleType;
use App\Models\ArticleTypeAttribute;
use App\Models\Header;
use App\Models\Quiz;
use App\Models\Reflection;
use App\Rules\NormalizeWhitespace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardArticleController extends Controller
{
    public function list(Request $request): View
    {
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column
        $allowedSorts = ['title', 'slug', 'excerpt', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $articles = Article::orderBy($sortBy, $sortDirection)
            ->get()
            ->map(function ($article) {
                $article->short_excerpt = Str::limit($article->excerpt, 40);
                return $article;
            });

        $isDemo = strtolower((string) config('app.demo')) === 'on';
        $articleCount = $articles->count();

        return view('dashboard-articles', compact('articles', 'sortBy', 'sortDirection', 'isDemo', 'articleCount'));
    }

    public function createArticle(): View|RedirectResponse
    {
        // Block create form when in demo mode and limit reached
        if (strtolower((string) config('app.demo')) === 'on' && Article::count() >= 5) {
            // Redirect back to list with a flag consumed by the UI
            return redirect()
                ->route('dashboard-articles')
                ->with('demo_limit_reached', true);
        }

        return view('dashboard-article-create-form');
    }

    public function storeArticle(Request $request): JsonResponse
    {
        try {
            // Enforce demo limit on server as well
            if (strtolower((string) config('app.demo')) === 'on' && Article::count() >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trial verzióban csak 5 cikket tudsz létrehozni',
                ], 403);
            }
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'excerpt' => ['nullable', 'string'],
            ]);

            // Normalize whitespace in title and excerpt
            $validated['title'] = NormalizeWhitespace::normalize($validated['title']);
            if (isset($validated['excerpt'])) {
                $validated['excerpt'] = NormalizeWhitespace::normalize($validated['excerpt']);
            }

            // Generate slug from title
            $slug = Str::slug($validated['title']);
            
            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Create the new article
            $article = Article::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'excerpt' => $validated['excerpt'] ?? '',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cikk sikeresen létrehozva.',
                'redirect' => route('dashboard-articles.show', ['slug' => $article->slug])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a cikk létrehozása során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyArticle(string $slug): JsonResponse
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();

            // Delete the article (this will cascade delete all article blocks due to foreign key constraint)
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cikk és kapcsolódó blokkok sikeresen törölve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a cikk törlése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(string $slug): View
    {
        $article = Article::with(['articleBlocks' => function($query) {
            $query->orderBy('position');
        }, 'articleBlocks.type'])->where('slug', $slug)->firstOrFail();
        
        // Load reflections for self-awareness blocks
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
            
        $reflections = [];
        if ($reflectionIds->isNotEmpty()) {
            $reflections = Reflection::whereIn('id', $reflectionIds)
                ->pluck('title', 'id')
                ->toArray();
        }
        
        // Load header data for this article
        $header = Header::where('article_id', $article->id)->first();
        
        return view('dashboard-article-show', compact('article', 'reflections', 'header'));
    }

    public function create(string $slug): View
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $types = ArticleType::with('articleTypeAttributes')->orderBy('name')->get();
        $reflections = Reflection::where('article_id', $article->id)->orderBy('title')->get(['id', 'title']);
        $quizzes = Quiz::where('article_id', $article->id)->orderBy('title')->get(['id', 'title']);
        
        return view('dashboard-article-create', compact('article', 'types', 'reflections', 'quizzes'));
    }

    public function store(Request $request, string $slug): JsonResponse
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();

            $validated = $request->validate([
                'type_id' => ['required', 'integer', Rule::exists('article_types', 'id')],
                'content' => ['nullable', 'string'],
                'reflection_id' => ['nullable', 'integer', Rule::exists('reflections', 'id')],
                'quiz_id' => ['nullable', 'integer', Rule::exists('quizzes', 'id')],
            ]);

            // Check if self-awareness type is selected
            $type = ArticleType::find($validated['type_id']);
            $content = null;

            if ($type && $type->name === 'self-awareness') {
                // For self-awareness type, use reflection_id as content
                if (isset($validated['reflection_id'])) {
                    $content = $validated['reflection_id'];
                }
            } elseif ($type && $type->name === 'quiz') {
                // For quiz type, use quiz_id as content
                if (isset($validated['quiz_id'])) {
                    $content = $validated['quiz_id'];
                }
            } else {
                // For other types, normalize whitespace in content
                if (isset($validated['content'])) {
                    $content = NormalizeWhitespace::normalize($validated['content']);
                }
            }

            // Get the next position (highest position + 1)
            $nextPosition = $article->articleBlocks()->max('position') + 1;

            // Create the new article block
            $block = $article->articleBlocks()->create([
                'type_id' => $validated['type_id'],
                'content' => $content,
                'position' => $nextPosition,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blokk sikeresen létrehozva.',
                'redirect' => route('dashboard-articles.show', ['slug' => $article->slug])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk létrehozása során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $slug, int $id): View
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $block = $article->articleBlocks()
            ->with('type')
            ->where('id', $id)
            ->firstOrFail();

        $types = ArticleType::with('articleTypeAttributes')->orderBy('name')->get();
        $reflections = Reflection::where('article_id', $article->id)->orderBy('title')->get(['id', 'title']);
        $quizzes = Quiz::where('article_id', $article->id)->orderBy('title')->get(['id', 'title']);

        return view('dashboard-article-edit', compact('article', 'block', 'types', 'reflections', 'quizzes'));
    }

    public function update(Request $request,  string $slug, int $id): JsonResponse
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();

            $block = $article->articleBlocks()
                ->where('id', $id)
                ->firstOrFail();

            $validated = $request->validate([
                'type_id' => ['required', 'integer', Rule::exists('article_types', 'id')],
                'content' => ['nullable', 'string'],
                'reflection_id' => ['nullable', 'integer', Rule::exists('reflections', 'id')],
                'quiz_id' => ['nullable', 'integer', Rule::exists('quizzes', 'id')],
            ]);

            // Check if self-awareness type is selected
            $type = ArticleType::find($validated['type_id']);
            $content = null;

            if ($type && $type->name === 'self-awareness') {
                // For self-awareness type, use reflection_id as content
                if (isset($validated['reflection_id'])) {
                    $content = $validated['reflection_id'];
                }
            } elseif ($type && $type->name === 'quiz') {
                // For quiz type, use quiz_id as content
                if (isset($validated['quiz_id'])) {
                    $content = $validated['quiz_id'];
                }
            } else {
                // For other types, normalize whitespace in content
                if (isset($validated['content'])) {
                    $content = NormalizeWhitespace::normalize($validated['content']);
                }
            }

            $block->update([
                'type_id' => $validated['type_id'],
                'content' => $content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blokk sikeresen mentve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk mentése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $slug, int $id): JsonResponse
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();

            $block = $article->articleBlocks()
                ->where('id', $id)
                ->firstOrFail();

            // Delete the block
            $block->delete();

            // Reorder all remaining blocks starting from position 1
            ArticleBlock::reorderForArticle($article->id);

            return response()->json([
                'success' => true,
                'message' => 'Blokk sikeresen törölve és újrarendezve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk törlése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request, string $slug): JsonResponse
    {
        try {
            $article = Article::where('slug', $slug)->firstOrFail();

            $validated = $request->validate([
                'positions' => 'required|array',
                'positions.*.id' => 'required|integer|exists:article_blocks,id',
                'positions.*.position' => 'required|integer|min:1',
            ]);

            // Update positions for each block
            foreach ($validated['positions'] as $positionData) {
                $block = $article->articleBlocks()
                    ->where('id', $positionData['id'])
                    ->first();

                if ($block) {
                    $block->update(['position' => $positionData['position']]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Blokkok sikeresen újrarendezve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az újrarendezés során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the article images management page
     */
    public function articleImages(): View
    {
        return view('dashboard-article-images');
    }

    /**
     * Upload multiple article images
     */
    public function uploadImages(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'images.*' => [
                    'required',
                    'image',
                    'mimes:jpeg,jpg,png,gif,webp',
                    'max:20480', // 20MB in KB
                ],
            ], [
                'images.*.required' => 'Legalább egy kép szükséges.',
                'images.*.image' => 'A fájlnak képnek kell lennie.',
                'images.*.mimes' => 'A kép csak jpeg, jpg, png, gif vagy webp formátumú lehet.',
                'images.*.max' => 'A kép mérete nem lehet nagyobb 20MB-nál.',
            ]);

            $uploadedImages = [];
            $errors = [];

            foreach ($request->file('images') as $index => $image) {
                try {
                    // Keep original filename
                    $filename = $image->getClientOriginalName();
                    
                    // Check if file already exists and handle conflicts
                    $originalFilename = $filename;
                    $counter = 1;
                    $pathInfo = pathinfo($filename);
                    $extension = $pathInfo['extension'] ?? '';
                    $nameWithoutExt = $pathInfo['filename'] ?? '';
                    
                    while (file_exists(public_path('img/article/' . $filename))) {
                        $filename = $nameWithoutExt . '_' . $counter . '.' . $extension;
                        $counter++;
                    }
                    
                    // Move file to public/img/article directory
                    $image->move(public_path('img/article'), $filename);
                    
                    $uploadedImages[] = $filename;
                } catch (\Exception $e) {
                    $errors[] = "Hiba a kép feltöltése során: " . $image->getClientOriginalName() . " - " . $e->getMessage();
                }
            }

            if (count($uploadedImages) > 0) {
                $message = count($uploadedImages) . ' kép sikeresen feltöltve.';
                if (count($errors) > 0) {
                    $message .= ' Figyelmeztetések: ' . implode(', ', $errors);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'uploaded_images' => $uploadedImages
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nem sikerült feltölteni egyetlen képet sem. ' . implode(', ', $errors)
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a képek feltöltése során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update article type attribute colors
     */
    public function updateTypeColors(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'article_type_id' => ['required', 'integer', Rule::exists('article_types', 'id')],
                'background' => ['nullable', 'string', 'max:7'],
                'text' => ['nullable', 'string', 'max:7'],
            ]);

            $typeAttribute = ArticleTypeAttribute::findOrFail($validated['article_type_id']);
            
            $typeAttribute->update([
                'background' => $validated['background'] ?? '',
                'text' => $validated['text'] ?? '',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Színek sikeresen frissítve.',
                'colors' => [
                    'background' => $typeAttribute->background,
                    'text' => $typeAttribute->text,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a színek frissítése során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset article type attribute colors to defaults
     */
    public function resetTypeColors(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'article_type_id' => ['required', 'integer', Rule::exists('article_types', 'id')],
            ]);

            $typeAttribute = ArticleTypeAttribute::findOrFail($validated['article_type_id']);
            
            $typeAttribute->update([
                'background' => $typeAttribute->default_background,
                'text' => $typeAttribute->default_text,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Színek visszaállítva az alapértelmezett értékekre.',
                'colors' => [
                    'background' => $typeAttribute->background,
                    'text' => $typeAttribute->text,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a színek visszaállítása során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an article image
     */
    public function deleteImage(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'image_name' => ['required', 'string'],
            ]);

            $imageName = $validated['image_name'];
            $imagePath = public_path('img/article/' . $imageName);

            // Security check: ensure the file is within the allowed directory
            $allowedPath = public_path('img/article/');
            $realImagePath = realpath($imagePath);
            $realAllowedPath = realpath($allowedPath);

            if (!$realImagePath || !$realAllowedPath || !str_starts_with($realImagePath, $realAllowedPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Érvénytelen képfájl elérési út.'
                ], 400);
            }

            // Check if file exists
            if (!file_exists($imagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'A képfájl nem található.'
                ], 404);
            }

            // Delete the file
            if (unlink($imagePath)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kép sikeresen törölve.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Hiba történt a kép törlése során.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a kép törlése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveHeader(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'subtitle' => ['required', 'string', 'max:255'],
                'image_url' => ['required', 'string'],
            ]);

            // Get article_id from the request or use a default
            $articleId = $request->input('article_id', 0); // 0 for onboarding

            // Check if header already exists for this article
            $existingHeader = Header::where('article_id', $articleId)->first();
            
            if ($existingHeader) {
                // Update existing header
                $existingHeader->update([
                    'title' => $validated['title'],
                    'subtitle' => $validated['subtitle'],
                    'image_url' => $validated['image_url'],
                ]);
            } else {
                // Create new header
                Header::create([
                    'article_id' => $articleId,
                    'title' => $validated['title'],
                    'subtitle' => $validated['subtitle'],
                    'image_url' => $validated['image_url'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Header sikeresen mentve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a header mentése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableImages(): JsonResponse
    {
        try {
            $imagePath = public_path('img/article');
            $images = [];
            
            if (is_dir($imagePath)) {
                $files = scandir($imagePath);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $images[] = $file;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a képek betöltése során: ' . $e->getMessage()
            ], 500);
        }
    }
}
