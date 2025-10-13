<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserReflectionNotes;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardStatisticsController extends Controller
{
    public function index(Request $request): View
    {
        // Get all articles for the filter dropdown
        $articles = Article::orderBy('title')->get(['id', 'title']);
        
        // Get date filters from request
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $articleId = $request->get('article_id');
        
        // Get the latest reflection note for each user-article combination
        $reflectionNotes = UserReflectionNotes::with(['googleUser', 'reflectionQuestion.reflection.article'])
            ->select('user_reflection_notes.*')
            ->whereIn('id', function($subQuery) use ($dateFrom, $dateTo, $articleId) {
                $subQuery->select(\DB::raw('MAX(user_reflection_notes.id)'))
                         ->from('user_reflection_notes')
                         ->join('reflection_questions', 'user_reflection_notes.reflection_question_id', '=', 'reflection_questions.id')
                         ->join('reflections', 'reflection_questions.reflection_id', '=', 'reflections.id')
                         ->whereNotNull('user_reflection_notes.note_text')
                         ->where('user_reflection_notes.note_text', '!=', '');
                
                // Apply date filters
                if ($dateFrom) {
                    $subQuery->whereDate('user_reflection_notes.updated_at', '>=', $dateFrom);
                }
                
                if ($dateTo) {
                    $subQuery->whereDate('user_reflection_notes.updated_at', '<=', $dateTo);
                }
                
                // Apply article filter
                if ($articleId) {
                    $subQuery->where('reflections.article_id', $articleId);
                }
                
                // Group by user and article to get only one entry per user per article
                $subQuery->groupBy([
                    'user_reflection_notes.google_user_id',
                    'reflection_questions.reflection_id'
                ]);
            })
            ->orderBy('user_reflection_notes.updated_at', 'desc')
            ->paginate(50)
            ->appends($request->query());
        
        return view('dashboard.statistics', compact('reflectionNotes', 'articles', 'dateFrom', 'dateTo', 'articleId'));
    }

    public function export(Request $request)
    {
        // Get date filters from request
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $articleId = $request->get('article_id');
        
        // Get the same data as the index method (without pagination)
        $reflectionNotes = UserReflectionNotes::with(['googleUser', 'reflectionQuestion.reflection.article'])
            ->select('user_reflection_notes.*')
            ->whereIn('id', function($subQuery) use ($dateFrom, $dateTo, $articleId) {
                $subQuery->select(\DB::raw('MAX(user_reflection_notes.id)'))
                         ->from('user_reflection_notes')
                         ->join('reflection_questions', 'user_reflection_notes.reflection_question_id', '=', 'reflection_questions.id')
                         ->join('reflections', 'reflection_questions.reflection_id', '=', 'reflections.id')
                         ->whereNotNull('user_reflection_notes.note_text')
                         ->where('user_reflection_notes.note_text', '!=', '');
                
                // Apply date filters
                if ($dateFrom) {
                    $subQuery->whereDate('user_reflection_notes.updated_at', '>=', $dateFrom);
                }
                
                if ($dateTo) {
                    $subQuery->whereDate('user_reflection_notes.updated_at', '<=', $dateTo);
                }
                
                // Apply article filter
                if ($articleId) {
                    $subQuery->where('reflections.article_id', $articleId);
                }
                
                // Group by user and article to get only one entry per user per article
                $subQuery->groupBy([
                    'user_reflection_notes.google_user_id',
                    'reflection_questions.reflection_id'
                ]);
            })
            ->orderBy('user_reflection_notes.updated_at', 'desc')
            ->get();
        
        // Generate filename
        $filename = 'jobbhazassag';
        if ($dateFrom && $dateTo) {
            $filename .= '_from_' . str_replace('-', '', $dateFrom) . '_to_' . str_replace('-', '', $dateTo);
        } elseif ($dateFrom) {
            $filename .= '_from_' . str_replace('-', '', $dateFrom);
        } elseif ($dateTo) {
            $filename .= '_to_' . str_replace('-', '', $dateTo);
        }
        
        // Create export class instance
        $export = new class($reflectionNotes) implements FromCollection, WithHeadings, WithStyles {
            private $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function collection()
            {
                return $this->data->map(function ($note) {
                    $article = null;
                    if ($note->reflectionQuestion && $note->reflectionQuestion->reflection) {
                        $article = $note->reflectionQuestion->reflection->article;
                    }
                    
                    return [
                        $note->googleUser ? $note->googleUser->name : 'Ismeretlen felhasználó',
                        $note->googleUser ? $note->googleUser->email : '',
                        $article ? $article->title : 'Ismeretlen cikk',
                        $note->updated_at->format('Y-m-d H:i:s')
                    ];
                });
            }
            
            public function headings(): array
            {
                return ['Név', 'Email', 'Cikk', 'Kitöltve'];
            }
            
            public function styles(Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }
        };
        
        return Excel::download($export, $filename . '.xlsx');
    }
}