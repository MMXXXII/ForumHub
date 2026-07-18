<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WallPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_user_id',
        'author_id',
        'body',
        'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function profileUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WallPost::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(WallPost::class, 'parent_id');
    }
}