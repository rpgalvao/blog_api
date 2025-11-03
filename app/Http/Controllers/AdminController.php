<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function getPosts(Request $request)
    {
        $user = $request->user();
        $perPage = 10;
        $posts = Post::where('authorId', $user->id)->paginate($perPage);
        $pagesPosts = [];
        foreach ($posts as $post) {
            $pagesPosts[] = [
                'id' => $post->id,
                'title' => $post->title,
                'cover' => $post->cover,
                'createdAt' => $post->createdAt,
                'authorName' => $post->author->name,
                'tags' => $post->tags->implode('name', ', '),
                'body' => $post->body,
                'slug' => $post->slug,
                'status' => $post->status,
            ];
        }
        return response()->json([
            'posts' => $pagesPosts,
            'page' => $posts->currentPage(),
        ]);
    }

    public function getPost(string $slug, Request $request)
    {
        $user = $request->user();
        $post = Post::where(['slug' => $slug, 'authorId' => $user->id])->first();
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'cover' => $post->cover,
            'createdAt' => $post->createdAt,
            'authorName' => $post->author->name,
            'tags' => $post->tags->implode('name', ', '),
            'body' => $post->body,
            'slug' => $post->slug,
            'status' => $post->status,
        ]);
    }

    public function deletePost(string $slug, Request $request)
    {
        $user = $request->user();
        $post = Post::where(['slug' => $slug, 'authorId' => $user->id])->first();
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function createPost(Request $request)
    {
        $user = $request->user();

        // Validar os dados enviados
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'in:DRAFT,PUBLISHED',
        ]);

        // Criar o post
        $post = new Post();
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->authorId = $user->id;
        if (!$request->has('status')) {
            $request->status = 'DRAFT';
        }

        // Gerar o slug baseado no título
        $post->slug = Str::slug($post->title) . '-' . uniqid();

        // Fazer o upload da imagem de capa (cover), se fornecida
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file'], 422);
            }
            if (!in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['error' => 'Invalid file type'], 422);
            }
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $post->cover = env('APP_URL'). '/uploads/' . $fileName;
        }
        $post->save();

        // Verificar as Tags e adiciona-las ao post
        if ($request->has('tags')) {
            $tags = explode(',', $request->input('tags'));
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tagName = trim($tagName);
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->attach($tagIds);

        }
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'cover' => $post->cover,
            'createdAt' => $post->createdAt,
            'authorName' => $post->author->name,
            'tags' => $post->tags->implode('name', ', '),
            'body' => $post->body,
            'slug' => $post->slug,
            'status' => $post->status,
        ], 201);
    }
}
