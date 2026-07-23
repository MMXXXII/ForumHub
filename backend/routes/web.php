<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\WallPostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/c/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/t/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');
Route::get('/u/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/t/create/new', [TopicController::class, 'create'])->name('topics.create');
    Route::post('/t', [TopicController::class, 'store'])->middleware('throttle:5,10')->name('topics.store');
    Route::delete('/t/{topic:slug}', [TopicController::class, 'destroy'])->name('topics.destroy');

    Route::post('/t/{topic:slug}/reply', [PostController::class, 'store'])->middleware('throttle:10,1')->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/u/{user}/wall', [WallPostController::class, 'store'])->middleware('throttle:10,1')->name('wall.store');
    Route::patch('/wall/{wallPost}/pin', [WallPostController::class, 'togglePin'])->name('wall.pin');
    Route::delete('/wall/{wallPost}', [WallPostController::class, 'destroy'])->name('wall.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read', [NotificationController::class, 'readAll'])->name('notifications.read');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:3,60');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/verify', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/verify', [TwoFactorController::class, 'store'])->middleware('throttle:5,1')->name('two-factor.store');
});

Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'profile'])->name('profile');
    Route::get('/security', [SettingsController::class, 'security'])->name('security');
    Route::get('/preferences', [SettingsController::class, 'preferences'])->name('preferences');

    Route::patch('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/password', [SettingsController::class, 'updatePassword'])->middleware('throttle:5,10')->name('password.update');
    Route::patch('/preferences', [SettingsController::class, 'updatePreferences'])->name('preferences.update');
});

Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/topics', [AdminTopicController::class, 'index'])->name('topics.index');
    Route::patch('/topics/{topic}/pin', [AdminTopicController::class, 'togglePin'])->name('topics.pin');
    Route::patch('/topics/{topic}/lock', [AdminTopicController::class, 'toggleLock'])->name('topics.lock');

    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::patch('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('posts.approve');
    Route::patch('/posts/{post}/reject', [AdminPostController::class, 'reject'])->name('posts.reject');

    Route::middleware('role:admin')->group(function () {
        Route::delete('/topics/{topic}', [AdminTopicController::class, 'destroy'])->name('topics.destroy');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
        Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });
});