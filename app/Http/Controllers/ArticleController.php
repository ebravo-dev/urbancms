<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('images')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datasheet' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store article
        $article = new Article();
        $article->title = $validated['title'];
        $article->description = $validated['description'];

        // Store datasheet if provided
        if ($request->hasFile('datasheet')) {
            $datasheetPath = $request->file('datasheet')->store('datasheets', 'public');
            $article->datasheet_path = $datasheetPath;
        }

        $article->save();

        // Store images if provided
        if ($request->hasFile('images')) {
            $order = 0;
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('article-images', 'public');

                $articleImage = new ArticleImage();
                $articleImage->article_id = $article->id;
                $articleImage->image_path = $imagePath;
                $articleImage->order = $order++;
                $articleImage->save();
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artículo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'datasheet' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:2048',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images.*' => 'nullable|integer',
        ]);

        // Update article
        $article->title = $validated['title'];
        $article->description = $validated['description'];

        // Update datasheet if provided
        if ($request->hasFile('datasheet')) {
            // Delete old datasheet if exists
            if ($article->datasheet_path) {
                Storage::disk('public')->delete($article->datasheet_path);
            }

            $datasheetPath = $request->file('datasheet')->store('datasheets', 'public');
            $article->datasheet_path = $datasheetPath;
        }

        $article->save();

        // Delete images if requested
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ArticleImage::find($imageId);
                if ($image && $image->article_id == $article->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Add new images if provided
        if ($request->hasFile('new_images')) {
            $maxOrder = $article->images()->max('order') ?? -1;
            $order = $maxOrder + 1;

            foreach ($request->file('new_images') as $image) {
                $imagePath = $image->store('article-images', 'public');

                $articleImage = new ArticleImage();
                $articleImage->article_id = $article->id;
                $articleImage->image_path = $imagePath;
                $articleImage->order = $order++;
                $articleImage->save();
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artículo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Delete all images
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete datasheet if exists
        if ($article->datasheet_path) {
            Storage::disk('public')->delete($article->datasheet_path);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artículo eliminado exitosamente');
    }

    /**
     * Reorder images
     */
    public function reorderImages(Request $request, Article $article)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|integer|exists:article_images,id',
        ]);

        foreach ($validated['images'] as $order => $imageId) {
            $image = ArticleImage::find($imageId);
            if ($image && $image->article_id == $article->id) {
                $image->order = $order;
                $image->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
