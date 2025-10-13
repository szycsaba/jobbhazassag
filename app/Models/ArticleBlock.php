<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class ArticleBlock extends Model
{
    protected $fillable = [
        'article_id',
        'position',
        'type_id',
        'content',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class, 'type_id');
    }

    /**
     * Reorder all blocks for a specific article starting from position 1
     */
    public static function reorderForArticle(int $articleId): void
    {
        $blocks = self::where('article_id', $articleId)
            ->orderBy('position')
            ->get();

        foreach ($blocks as $index => $block) {
            $newPosition = $index + 1;
            if ($block->position !== $newPosition) {
                $block->update(['position' => $newPosition]);
            }
        }
    }
}
