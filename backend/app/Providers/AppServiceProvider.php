<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
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
        View::composer('layouts.app', function ($view) {
            $view->with('sidebarCategories', Category::withCount('topics')->orderBy('order')->get());
            $view->with('sidebarUsers', User::orderBy('name')->take(10)->get());

            if (auth()->check()) {
                $view->with('navNotifications', auth()->user()->notifications()->with('actor')->take(6)->get());
                $view->with('navUnreadCount', auth()->user()->unreadNotificationsCount());
            }
        });

        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers();
        });
    }
}
