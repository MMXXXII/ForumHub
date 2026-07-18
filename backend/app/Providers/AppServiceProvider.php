<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('sidebarCategories', Category::orderBy('order')->get());
            $view->with('sidebarUsers', User::orderBy('name')->take(10)->get());
        });


        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers();
        });
    }
}