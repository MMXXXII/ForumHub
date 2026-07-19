<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function profile(Request $request)
    {
        return view('settings.profile', ['user' => $request->user()]);
    }

    public function security(Request $request)
    {
        return view('settings.security', ['user' => $request->user()]);
    }

    public function preferences(Request $request)
    {
        return view('settings.preferences', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $strip = function (?string $value, array $domains): ?string {
            $value = trim((string) $value);
            if ($value === '') {
                return null;
            }
            $value = preg_replace('~^https?://~i', '', $value);
            foreach ($domains as $domain) {
                $value = preg_replace('~^(www\.)?'.preg_quote($domain, '~').'/~i', '', $value);
            }
            return trim($value, " /@");
        };

        $request->merge([
            'telegram' => $strip($request->input('telegram'), ['t.me', 'telegram.me']),
            'vk' => $strip($request->input('vk'), ['vk.com', 'vk.ru', 'm.vk.com']),
            'steam' => $strip($request->input('steam'), ['steamcommunity.com/id', 'steamcommunity.com/profiles', 'steamcommunity.com']),
        ]);
        
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'max:100'],
            'birthday' => ['nullable', 'date', 'before:today', 'after:1920-01-01'],
            'telegram' => ['nullable', 'string', 'max:64', 'regex:/^@?[A-Za-z0-9_]{3,}$/'],
            'vk' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9_.]{3,}$/'],
            'steam' => ['nullable', 'string', 'max:64', 'regex:/^[A-Za-z0-9_-]{2,}$/'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
        ], [
            'telegram.regex' => 'Некорректный логин Telegram.',
            'vk.regex' => 'Некорректный логин ВКонтакте.',
            'steam.regex' => 'Некорректный логин Steam.',
        ]);

        $data = [
            'status' => $validated['status'] ?? null,
            'birthday' => $validated['birthday'] ?? null,
            'telegram' => isset($validated['telegram']) ? ltrim($validated['telegram'], '@') : null,
            'vk' => $validated['vk'] ?? null,
            'steam' => $validated['steam'] ?? null,
            'website' => $validated['website'] ?? null,
        ];

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('status', 'Профиль обновлён.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Текущий пароль указан неверно.',
        ]);

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('status', 'Пароль изменён.');
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'timezone' => ['required', 'timezone'],
        ]);

        $request->user()->update(['timezone' => $validated['timezone']]);

        return back()->with('status', 'Настройки сохранены.');
    }
}