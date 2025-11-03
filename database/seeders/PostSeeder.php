<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post1 = Post::create([
            'slug' => 'slug-de-teste-1',
            'authorId' => 1,
            'title' => 'Titulo de Teste 1',
            'body' => '1 - Conteudo de teste para popular a tabela de posts.',
            'cover' => 'https://picsum.photos/200/300',
            'createdAt' => now(),
            'status' => 'PUBLISHED'
        ]);
        $post1->tags()->attach([1, 2]);

        $post2 = Post::create([
            'slug' => 'slug-de-teste-2',
            'authorId' => 1,
            'title' => 'Titulo de Teste 2',
            'body' => '2 - Conteudo de teste para popular a tabela de posts.',
            'cover' => 'https://picsum.photos/200/300',
            'createdAt' => now(),
            'status' => 'PUBLISHED'
        ]);
        $post2->tags()->attach([1, 3]);

        $post3 = Post::create([
            'slug' => 'slug-de-teste-3',
            'authorId' => 1,
            'title' => 'Titulo de Teste 3',
            'body' => '3 - Conteudo de teste para popular a tabela de posts.',
            'cover' => 'https://picsum.photos/200/300',
            'createdAt' => now(),
            'status' => 'DRAFT'
        ]);
        $post3->tags()->attach([3, 4]);

        $post4 = Post::create([
            'slug' => 'slug-de-teste-4',
            'authorId' => 1,
            'title' => 'Titulo de Teste 4',
            'body' => '4 - Conteudo de teste para popular a tabela de posts.',
            'cover' => 'https://picsum.photos/200/300',
            'createdAt' => now(),
            'status' => 'PUBLISHED'
        ]);
        $post4->tags()->attach([1, 2, 3, 4]);
    }
}
