<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getPosts(Request $request)
    {
        $perPage = 10;
        $posts = Post::where('status', 'PUBLISHED')->paginate($perPage);
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
            ];
        }
        return response()->json([
            'posts' => $pagesPosts,
            'page' => $posts->currentPage(),
        ]);
    }

    public function getPost(string $slug)
    {
        $post = Post::where(['slug' => $slug, 'status' => 'PUBLISHED'])->first();
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
        ]);
    }

    public function getRelatedPosts(string $slug)
    {
        //Buscar o post pelo slug
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        //Pegar as tags do post
        $tagsList = $post->tags->pluck('id');

        //Buscar outros posts que tenham pelo menos uma das tags
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->whereHas('tags', function ($query) use ($tagsList) {
                $query->whereIn('tags.id', $tagsList)->where('posts.status', 'PUBLISHED');
            })
            ->limit(5)
            ->get();

        //Retornar o resultado
        return response()->json([
            'posts' => $relatedPosts->map(function ($posts) {
                return [
                    'id' => $posts->id,
                    'title' => $posts->title,
                    'cover' => $posts->cover,
                    'createdAt' => $posts->createdAt,
                    'authorName' => $posts->author->name,
                    'tags' => $posts->tags->implode('name', ', '),
                    'body' => $posts->body,
                    'slug' => $posts->slug,
                ];
            })
        ]);
    }
}
