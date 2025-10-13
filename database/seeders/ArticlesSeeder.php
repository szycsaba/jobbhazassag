<?php

namespace Database\Seeders;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'id' => 1,
                'title' => 'Kapcsolati törések',
                'slug' => 'kapcsolati-toresek',
                'excerpt' => 'Ebben a leckében a kapcsolati törésekről lesz szó',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
