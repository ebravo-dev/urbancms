<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::with(['images' => function ($query) {
            $query->orderBy('display_order', 'asc');
        }])->orderBy('publication_date', 'desc');

        // Filter by search term
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('keywords', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('publication_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('publication_date', '<=', $request->date_to);
        }

        // Pagination
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $articles = $query->paginate($perPage);

        // Transform the data
        $articles->getCollection()->transform(function ($article) {
            return $this->formatArticle($article, false);
        });

        return response()->json([
            'success' => true,
            'data' => $articles->items(),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'from' => $articles->firstItem(),
                'to' => $articles->lastItem(),
            ],
            'links' => [
                'first' => $articles->url(1),
                'last' => $articles->url($articles->lastPage()),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
            ]
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        $article->load([
            'images' => function ($query) {
                $query->orderBy('display_order', 'asc');
            },
            'comments' => function ($query) {
                $query->where('is_approved', true)
                      ->orderBy('created_at', 'desc');
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->formatArticle($article, true)
        ]);
    }

    /**
     * Get featured/latest articles.
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 5), 10); // Max 10 featured articles

        $articles = Article::with(['images' => function ($query) {
            $query->orderBy('display_order', 'asc');
        }])
            ->orderBy('publication_date', 'desc')
            ->limit($limit)
            ->get();

        $formattedArticles = $articles->map(function ($article) {
            return $this->formatArticle($article, false);
        });

        return response()->json([
            'success' => true,
            'data' => $formattedArticles
        ]);
    }

    /**
     * Get related articles based on keywords.
     */
    public function related(Article $article, Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 3), 5); // Max 5 related articles

        $relatedArticles = collect();

        if ($article->keywords) {
            $keywords = explode(',', $article->keywords);
            $keywords = array_map('trim', $keywords);

            $query = Article::with(['images' => function ($query) {
                $query->orderBy('display_order', 'asc');
            }])
                ->where('id', '!=', $article->id)
                ->orderBy('publication_date', 'desc');

            // Search for articles with similar keywords
            foreach ($keywords as $keyword) {
                $query->orWhere('keywords', 'like', "%{$keyword}%");
            }

            $relatedArticles = $query->limit($limit)->get();
        }

        // If we don't have enough related articles, fill with latest ones
        if ($relatedArticles->count() < $limit) {
            $remaining = $limit - $relatedArticles->count();
            $latestArticles = Article::with(['images' => function ($query) {
                $query->orderBy('display_order', 'asc');
            }])
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->orderBy('publication_date', 'desc')
                ->limit($remaining)
                ->get();

            $relatedArticles = $relatedArticles->merge($latestArticles);
        }

        $formattedArticles = $relatedArticles->map(function ($article) {
            return $this->formatArticle($article, false);
        });

        return response()->json([
            'success' => true,
            'data' => $formattedArticles
        ]);
    }

    /**
     * Format article data for API response.
     */
    private function formatArticle(Article $article, bool $includeFullContent = false): array
    {
        $formattedArticle = [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'description' => $article->description,
            'publication_date' => $article->publication_date->toISOString(),
            'publication_date_formatted' => $article->publication_date->format('d M Y'),
            'meta_title' => $article->meta_title,
            'meta_description' => $article->meta_description,
            'keywords' => $article->keywords ? explode(',', $article->keywords) : [],
            'images' => $article->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->image_path),
                    'display_order' => $image->display_order
                ];
            }),
            'featured_image' => $article->images->first() ?
                asset('storage/' . $article->images->first()->image_path) : null,
            'created_at' => $article->created_at->toISOString(),
            'updated_at' => $article->updated_at->toISOString(),
        ];

        // Include full content only when explicitly requested (single article view)
        if ($includeFullContent) {
            $formattedArticle['content'] = $article->content;
            $formattedArticle['content_html'] = $this->renderContentAsHtml($article->content);

            // Include comments if available
            if ($article->comments) {
                $formattedArticle['comments'] = $article->comments
                    ->where('is_approved', true)
                    ->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'name' => $comment->name,
                            'message' => $comment->message,
                            'created_at' => $comment->created_at->toIso8601String(),
                            'created_at_formatted' => $comment->created_at->format('d M Y H:i')
                        ];
                    });
            }
        } else {
            // For listing, provide a preview of the content
            $formattedArticle['content_preview'] = $this->generateContentPreview($article->content);
        }

        return $formattedArticle;
    }

    /**
     * Generate a text preview from JSON content.
     */
    private function generateContentPreview(array $content): string
    {
        $preview = '';
        $wordCount = 0;
        $maxWords = 30;

        foreach ($content as $block) {
            if ($block['type'] === 'paragraph' && $wordCount < $maxWords) {
                $blockWords = explode(' ', $block['content']);
                $remainingWords = $maxWords - $wordCount;

                if (count($blockWords) <= $remainingWords) {
                    $preview .= $block['content'] . ' ';
                    $wordCount += count($blockWords);
                } else {
                    $preview .= implode(' ', array_slice($blockWords, 0, $remainingWords)) . '...';
                    break;
                }
            }
        }

        return trim($preview);
    }

    /**
     * Render JSON content as HTML.
     */
    private function renderContentAsHtml(array $content): string
    {
        $html = '';

        foreach ($content as $block) {
            switch ($block['type']) {
                case 'heading':
                    $html .= '<h2>' . htmlspecialchars($block['content']) . '</h2>';
                    break;
                case 'subtitle':
                    $html .= '<h3>' . htmlspecialchars($block['content']) . '</h3>';
                    break;
                case 'paragraph':
                    $html .= '<p>' . htmlspecialchars($block['content']) . '</p>';
                    break;
                case 'quote':
                    $html .= '<blockquote>' . htmlspecialchars($block['content']) . '</blockquote>';
                    break;
                case 'list':
                    $items = explode("\n", $block['content']);
                    $html .= '<ul>';
                    foreach ($items as $item) {
                        if (trim($item)) {
                            $html .= '<li>' . htmlspecialchars(trim($item)) . '</li>';
                        }
                    }
                    $html .= '</ul>';
                    break;
                default:
                    $html .= '<p>' . htmlspecialchars($block['content']) . '</p>';
            }
        }

        return $html;
    }
}
