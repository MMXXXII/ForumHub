<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;

class TopicController extends Controller
{
    public function togglePin(Topic $topic)
    {
        $topic->update(['is_pinned' => ! $topic->is_pinned]);

        return back()->with('status', $topic->is_pinned ? 'Тема закреплена.' : 'Тема откреплена.');
    }

    public function toggleLock(Topic $topic)
    {
        $topic->update(['is_locked' => ! $topic->is_locked]);

        return back()->with('status', $topic->is_locked ? 'Тема закрыта.' : 'Тема открыта.');
    }
}