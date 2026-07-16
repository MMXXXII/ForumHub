<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('sidebarCategories', Category::orderBy('order')->get());
        });

        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers();
        });
    }
}