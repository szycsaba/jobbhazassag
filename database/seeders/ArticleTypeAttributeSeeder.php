<?php

namespace Database\Seeders;

use App\Models\ArticleTypeAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTypeAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articleTypeAttributes = [
            [
                'article_type_id' => 3,
                'background' => '',
                'text' => '#3b3b3b',
                'default_background' => '',
                'default_text' => '#3b3b3b',
            ],
            [
                'article_type_id' => 4,
                'background' => '#326E6C',
                'text' => '#FFFFFF',
                'default_background' => '#326E6C',
                'default_text' => '#FFFFFF',
            ],
        ];

        foreach ($articleTypeAttributes as $articleTypeAttribute) {
            ArticleTypeAttribute::create($articleTypeAttribute);
        }
    }
}
