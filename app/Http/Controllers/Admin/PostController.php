<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Listing\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])
            ->latest('published_at')
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'is_hidden' => 'nullable|boolean',
            'hidden_reason' => 'nullable|string|max:500',
            'moderation_comment' => 'nullable|string|max:1000',
        ]);

        $isHidden = $request->boolean('is_hidden');

        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? now(),
            'is_hidden' => $isHidden,
            'hidden_reason' => $isHidden ? ($request->hidden_reason ?? 'Hidden by admin.') : null,
            'moderation_comment' => $request->input('moderation_comment') ?: ($isHidden ? ($request->hidden_reason ?? 'Hidden by admin.') : null),
        ]);

        if ($request->hasFile('featured_image')) {
            $post->clearMediaCollection('images');
            $post->addMedia($request->file('featured_image'))->toMediaCollection('images');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $post->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }
}
