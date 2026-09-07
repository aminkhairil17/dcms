<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    /** @var bool Panel dropdown open state */
    public bool $open = false;

    public function markAsRead(string $id): void
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && (string) $notification->notifiable_id === (string) Auth::id()) {
            $notification->markAsRead();
        }

        $this->open = false;
    }

    /**
     * Mark a single notification as read and redirect to the target URL.
     */
    public function markAsReadAndRedirect(string $id, string $url = ''): mixed
    {
        $this->markAsRead($id);

        if (filled($url) && $url !== '#') {
            return redirect($url);
        }

        return null;
    }

    /**
     * Mark all notifications for the current user as read.
     */
    public function markAllAsRead(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $user?->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $notifications = $user
            ?->notifications()
            ->latest()
            ->take(15)
            ->get() ?? collect();

        $unreadCount = $user?->unreadNotifications()->count() ?? 0;

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
