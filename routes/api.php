<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/validate', [AuthController::class, 'validate'])->middleware('auth:sanctum');
});

// Rotas públicas
Route::get('/posts', [PostController::class, 'getPosts']);
Route::get('/post/{slug}', [PostController::class, 'getPost']);
Route::get('/posts/{slug}/related', [PostController::class, 'getRelatedPosts']);

// Rotas de gestão (privadas)
/*
 * - necessitam de autenticação (Bearer)
 * - retornam e atuam somente em posts do usuário autenticado
 * - prefixo /admin
 *
 * admin/posts - (pegar todos os posts, inclusive os em DRAFT e PUBLISHED, com paginação)
 * admin/post/{slug} - (pegar um único post específico)
 * POST admin/post - (criar um novo post)
 * PUT admin/post/{slug} - (atualizar um post específico)
 * DELETE admin/post/{slug} - (deletar um post específico)
 *
 * */
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [AdminController::class, 'getPosts']);
    Route::get('/post/{slug}', [AdminController::class, 'getPost']);
    Route::delete('/post/{slug}', [AdminController::class, 'deletePost']);
    Route::post('/post', [AdminController::class, 'createPost']);
});
