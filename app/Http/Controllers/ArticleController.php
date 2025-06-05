<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the articles.
     */
    public function index()
    {
        $articles = Article::orderBy('publication_date', 'desc')->paginate(10);
        return view('blog.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        return view('blog.articles.create');
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        // Debug: Ver qué se está recibiendo
        Log::info('Datos recibidos en ArticleController: ' . json_encode($request->all()));
        Log::info('Campo content recibido: ' . $request->input('content'));

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'publication_date' => 'nullable|date',
            'content' => 'required|string', // Cambiar de json a string para mayor flexibilidad
            'images.*' => 'nullable|image|max:2048',
            'datasheet' => 'nullable|file|max:2048|mimes:pdf,doc,docx,jpg,jpeg,png',
            'meta_title' => 'nullable|string|max:70', // Recomendación SEO para meta título
            'meta_description' => 'nullable|string|max:160', // Recomendación SEO para meta descripción
            'keywords' => 'nullable|string|max:255',
        ]);

        // Decodificar y validar estructura del JSON de contenido
        $contentData = json_decode($request->content, true);
        if (!is_array($contentData)) {
            Log::error('Error decodificando JSON: ' . $request->content);
            return back()->withErrors(['content' => 'El formato del contenido es inválido.']);
        }

        // Validar estructura de cada bloque
        foreach ($contentData as $block) {
            if (
                !isset($block['type']) || !isset($block['content']) ||
                !in_array($block['type'], ['paragraph', 'heading', 'subtitle', 'quote', 'list'])
            ) {
                return back()->withErrors(['content' => 'Uno o más bloques tienen un formato inválido.']);
            }
        }

        $article = Article::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'publication_date' => $request->publication_date ?? now(),
            'content' => $contentData, // Guardar como array ya validado
            'datasheet_path' => $request->hasFile('datasheet') ? $request->file('datasheet')->store('datasheets', 'public') : null,
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
        ]);

        if ($request->hasFile('images')) {
            $order = 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('articles', 'public');

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_path' => $path,
                    'display_order' => $order++,
                ]);
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artículo creado exitosamente');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        $article->load(['images' => function ($query) {
            $query->orderBy('display_order', 'asc');
        }, 'comments']);

        return view('blog.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        $article->load(['images' => function ($query) {
            $query->orderBy('display_order', 'asc');
        }]);

        return view('blog.articles.edit', compact('article'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publication_date' => 'nullable|date',
            'content' => 'required|json',
            'images.*' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'keywords' => 'nullable|string|max:255',
        ]);

        // Decodificar y validar estructura del JSON de contenido
        $contentData = json_decode($request->content, true);
        if (!is_array($contentData)) {
            return back()->withErrors(['content' => 'El formato del contenido es inválido.']);
        }

        // Validar estructura de cada bloque
        foreach ($contentData as $block) {
            if (
                !isset($block['type']) || !isset($block['content']) ||
                !in_array($block['type'], ['paragraph', 'heading', 'subtitle', 'quote', 'list'])
            ) {
                return back()->withErrors(['content' => 'Uno o más bloques tienen un formato inválido.']);
            }
        }

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'publication_date' => $request->publication_date ?? now(),
            'content' => $contentData, // Guardar como array ya validado
            'meta_title' => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
        ]);

        if ($request->hasFile('images')) {
            $maxOrder = $article->images()->max('display_order') ?? -1;
            foreach ($request->file('images') as $image) {
                $path = $image->store('articles', 'public');

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_path' => $path,
                    'display_order' => ++$maxOrder,
                ]);
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artículo actualizado exitosamente');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article)
    {
        // Delete associated images from storage
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artículo eliminado exitosamente');
    }

    /**
     * Reorder images for an article
     */
    public function reorderImages(Request $request, Article $article)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'exists:article_images,id',
        ]);

        $order = 0;
        foreach ($request->images as $imageId) {
            ArticleImage::find($imageId)->update(['display_order' => $order++]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete an image from an article
     */
    public function deleteImage(ArticleImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Imagen eliminada exitosamente');
    }
}
