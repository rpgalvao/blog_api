<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tag1 = Tag::create(['name' => 'PHP']);
        $tag2 = Tag::create(['name' => 'Laravel']);
        $tag3 = Tag::create(['name' => 'JavaScript']);
        $tag4 = Tag::create(['name' => 'Pyton']);
    }
}
