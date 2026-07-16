<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/c/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/t/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');