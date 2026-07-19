<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isBanned()) {
            $until = $user->isPermanentlyBanned()
                ? 'навсегда'
                : 'до '.$user->banned_until->timezone('Asia/Irkutsk')->format('d.m.Y H:i');

            $reason = $user->ban_reason ? ' Причина: '.$user->ban_reason : '';

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Ваш аккаунт заблокирован '.$until.'.'.$reason,
            ]);
        }

        return $next($request);
    }
}