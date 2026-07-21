<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar', 'status', 'birthday', 'telegram', 'vk', 'steam', 'website', 'timezone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned_until' => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'birthday' => 'date',
        ];
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['moderator', 'admin'], true);
    }

    public function roleColor(): string
    {
        return match ($this->role) {
            'admin' => 'text-red-600',
            'moderator' => 'text-green-600',
            default => 'text-black',
        };
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Администратор',
            'moderator' => 'Модератор',
            default => 'Пользователь',
        };
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    public function wallPosts()
    {
        return $this->hasMany(WallPost::class, 'profile_user_id');
    }

    public function isBanned(): bool
    {
        return $this->banned_until !== null && $this->banned_until->isFuture();
    }

    public function isPermanentlyBanned(): bool
    {
        return $this->isBanned() && $this->banned_until->year > 2100;
    }

    public function socialLinks(): array
    {
        return array_filter([
            'Telegram' => $this->telegram ? 'https://t.me/'.ltrim($this->telegram, '@') : null,
            'ВКонтакте' => $this->vk ? 'https://vk.com/'.$this->vk : null,
            'Steam' => $this->steam ? 'https://steamcommunity.com/id/'.$this->steam : null,
            'Сайт' => $this->website,
        ]);
    }
}
