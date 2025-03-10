<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with(['user', 'category', 'media'])->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_category_id' => 'required|exists:post_categories,id',
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = Post::create($request->only(['user_id', 'post_category_id', 'title', 'content']));

        if ($request->hasFile('image')) {
            $post->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('media'),
        ], 201);
    }
   
    public function show(Post $post)
    {
        $post->load(['user', 'category']);

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'images' => $post->getMedia('images')->map(fn($media) => $media->getUrl()),
            'user' => $post->user,
            'category' => $post->category,
        ], 200);
    }


    public function update(Request $request, Post $post)
    {
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
    
        $post->update($request->all());
        return response()->json($post, 200);
    }
    
    public function destroy(Post $post)
    {
        $post->clearMediaCollection('images');
        $post->delete();
    
        return response()->json(null, 204);
    }
}
