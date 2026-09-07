<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BookmarkTopbarIcon extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $this->count = Cache::remember(
                'nav_badge_bookmarks_'.$userId,
                30,
                fn () => DB::table('document_bookmarks')->where('user_id', $userId)->count()
            );
        }
    }

    public function render()
    {
        return view('livewire.bookmark-topbar-icon');
    }
}
