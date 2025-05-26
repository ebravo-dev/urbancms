<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'publication_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'content' => '<h2>' . $this->faker->sentence(8) . '</h2>' .
                '<p>' . $this->faker->paragraph(5) . '</p>' .
                '<p>' . $this->faker->paragraph(7) . '</p>' .
                '<h3>' . $this->faker->sentence(4) . '</h3>' .
                '<p>' . $this->faker->paragraph(6) . '</p>',
        ];
    }
}
