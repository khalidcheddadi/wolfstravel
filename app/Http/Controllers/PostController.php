<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Listing\Category;
use App\Services\ImageOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::visible()
                    ->with(['category', 'user'])
                    ->latest('published_at')
                    ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::visible()
                    ->with(['category', 'user'])
                    ->where('slug', $slug)
                    ->firstOrFail();
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $optimized = ImageOptimizationService::optimizeUploadedImage(
                $request->file('featured_image'),
                1800,
                1200,
                2 * 1024 * 1024,
                88
            );

            $imagePath = $optimized->store('posts', 'public');
        }

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'category_id' => $validated['category_id'] ?? null,
            'user_id' => Auth::id(),
            'is_published' => $request->has('is_published'),
            'published_at' => $request->input('published_at') ?? ($request->has('is_published') ? now() : null),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $optimizedImage = ImageOptimizationService::optimizeUploadedImage(
                    $image,
                    1800,
                    1200,
                    2 * 1024 * 1024,
                    88
                );

                $post->addMedia($optimizedImage)->toMediaCollection('images');
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }
}