<?php

namespace Database\Seeders;

use App\Models\ArticleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => 1,
                'name' => 'title',
            ],
            [
                'id' => 2,
                'name' => 'subtitle',
            ],
            [
                'id' => 3,
                'name' => 'yellow',
            ],
            [
                'id' => 4,
                'name' => 'white',
            ],
            [
                'id' => 5,
                'name' => 'green',
            ],
            [
                'id' => 6,
                'name' => 'image',
            ],
            [
                'id' => 7,
                'name' => 'columns',
            ],
            [
                'id' => 8,
                'name' => 'big-title',
            ],
            [
                'id' => 9,
                'name' => 'column-title',
            ],
            [
                'id' => 10,
                'name' => 'left-subtitle',
            ],
            [
                'id' => 11,
                'name' => 'self-awareness',
            ],
            [
                'id' => 12,
                'name' => 'quiz',
            ]
        ];

        foreach ($types as $type) {
            ArticleType::create($type);
        }
    }
}
