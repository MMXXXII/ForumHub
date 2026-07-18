<?php
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WallPostController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/c/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/t/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');
Route::post('/t/{topic:slug}/reply', [PostController::class, 'store'])
    ->middleware('auth')
    ->name('posts.store');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('auth')->name('posts.destroy');
Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware('auth')->name('posts.update');
Route::delete('/t/{topic:slug}', [TopicController::class, 'destroy'])->middleware('auth')->name('topics.destroy');
Route::get('/t/create/new', [TopicController::class, 'create'])->middleware('auth')->name('topics.create');
Route::post('/t', [TopicController::class, 'store'])->middleware('auth')->name('topics.store');
Route::get('/u/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::post('/u/{user}/wall', [WallPostController::class, 'store'])->middleware('auth')->name('wall.store');
Route::patch('/wall/{wallPost}/pin', [WallPostController::class, 'togglePin'])->middleware('auth')->name('wall.pin');
Route::delete('/wall/{wallPost}', [WallPostController::class, 'destroy'])->middleware('auth')->name('wall.destroy');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/verify', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/verify', [TwoFactorController::class, 'store'])->name('two-factor.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/topics', [AdminTopicController::class, 'index'])->name('topics.index');
    Route::patch('/topics/{topic}/pin', [AdminTopicController::class, 'togglePin'])->name('topics.pin');
    Route::patch('/topics/{topic}/lock', [AdminTopicController::class, 'toggleLock'])->name('topics.lock');

    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');

    Route::middleware('role:admin')->group(function () {
        Route::delete('/topics/{topic}', [AdminTopicController::class, 'destroy'])->name('topics.destroy');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });
});