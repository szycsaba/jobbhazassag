<?php

namespace App\Http\Controllers;

use App\Models\OnsiteBlock;
use App\Models\ArticleType;
use App\Models\Header;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardOnboardingController extends Controller
{
    public function index(): View
    {
        $onsiteBlocks = OnsiteBlock::with('type')
            ->orderBy('position')
            ->get();
        
        // Load header data for onboarding (using a special article_id of 0 for onboarding)
        $header = Header::where('article_id', 0)->first();
            
        return view('dashboard-onboarding', compact('onsiteBlocks', 'header'));
    }

    public function create(): View
    {
        $types = ArticleType::with('articleTypeAttributes')->orderBy('name')->get();
        
        return view('dashboard-onboarding-create', compact('types'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type_id' => 'required|exists:article_types,id',
                'content' => 'required|string',
            ]);

            // Get the next position
            $nextPosition = OnsiteBlock::max('position') + 1;

            OnsiteBlock::create([
                'type_id' => $request->type_id,
                'content' => $request->content,
                'position' => $nextPosition,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Onboarding blokk sikeresen létrehozva.',
                'redirect' => route('dashboard-onboarding')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk létrehozása során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(int $id): View
    {
        $block = OnsiteBlock::with('type')->findOrFail($id);
        $types = ArticleType::with('articleTypeAttributes')->orderBy('name')->get();

        return view('dashboard-onboarding-edit', compact('block', 'types'));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type_id' => 'required|exists:article_types,id',
                'content' => 'required|string',
            ]);

            $block = OnsiteBlock::findOrFail($id);
            
            $block->update([
                'type_id' => $request->type_id,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Onboarding blokk sikeresen frissítve.',
                'redirect' => route('dashboard-onboarding')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk frissítése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $block = OnsiteBlock::findOrFail($id);
            $block->delete();

            return response()->json([
                'success' => true,
                'message' => 'Onboarding blokk sikeresen törölve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a blokk törlése során: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'positions' => 'required|array',
                'positions.*.id' => 'required|integer|exists:onsite_blocks,id',
                'positions.*.position' => 'required|integer|min:1'
            ]);

            foreach ($request->positions as $positionData) {
                OnsiteBlock::where('id', $positionData['id'])->update(['position' => $positionData['position']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Onboarding blokkok sorrendje sikeresen frissítve.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a sorrend frissítése során: ' . $e->getMessage()
            ], 500);
        }
    }
}
