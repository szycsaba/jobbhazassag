<?php

namespace App\Observers;

use App\Models\ArticleBlock;
use App\Models\Article;

class ArticleBlockObserver
{
    /**
     * Handle the ArticleBlock "created" event.
     */
    public function created(ArticleBlock $articleBlock): void
    {
        $this->touchParentArticle($articleBlock);
    }

    /**
     * Handle the ArticleBlock "updated" event.
     */
    public function updated(ArticleBlock $articleBlock): void
    {
        $this->touchParentArticle($articleBlock);
    }

    /**
     * Handle the ArticleBlock "deleted" event.
     */
    public function deleted(ArticleBlock $articleBlock): void
    {
        $this->touchParentArticle($articleBlock);
    }

    /**
     * Touch the parent article's updated_at timestamp
     */
    private function touchParentArticle(ArticleBlock $articleBlock): void
    {
        $article = Article::find($articleBlock->article_id);
        if ($article) {
            $article->touch();
        }
    }
}
