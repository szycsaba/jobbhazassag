<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Support\MarkupTransformer;
use App\Models\ArticleBlock;
use App\Observers\ArticleBlockObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('markup', function ($expr) {
            return "<?php echo app(".MarkupTransformer::class."::class)->toHtml($expr); ?>";
        });

        // Register model observers
        ArticleBlock::observe(ArticleBlockObserver::class);
    }
}
