<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 articles with comments
        Article::factory()
            ->count(10)
            ->has(Comment::factory()->count(3)) // Each article has 3 comments
            ->create()
            ->each(function ($article) {
                // Add placeholder images for each article
                for ($i = 1; $i <= 2; $i++) {
                    ArticleImage::create([
                        'article_id' => $article->id,
                        'image_path' => 'placeholder/blog-image-' . $i . '.jpg',
                        'display_order' => $i - 1,
                    ]);
                }
            });
    }
}
