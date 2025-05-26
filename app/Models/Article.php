<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'publication_date',
        'content',
        'slug',
        'meta_title',
        'meta_description',
        'keywords',
    ];

    protected $casts = [
        'publication_date' => 'datetime',
        'content' => 'array', // Cast automático entre JSON en la DB y array en PHP
    ];

    /**
     * Get the images for the article.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ArticleImage::class);
    }

    /**
     * Get the comments for the article.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
