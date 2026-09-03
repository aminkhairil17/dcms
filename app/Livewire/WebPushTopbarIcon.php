<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Notifications\WebPushGenericNotification;

class WebPushTopbarIcon extends Component
{
    public bool $isSubscribed = false;

    protected $listeners = ['web-push-status-changed' => 'updateStatus'];

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        if (Auth::check()) {
            $this->isSubscribed = Auth::user()->pushSubscriptions()->count() > 0;
        }
    }

    public function updateStatus(string $status): void
    {
        $this->isSubscribed = ($status === 'subscribed');
    }

    public function sendTestNotification(): void
    {
        $user = Auth::user();
        if (!$user) return;

        if ($user->pushSubscriptions()->count() === 0) {
            $this->dispatch('show-toast', message: 'Silakan aktifkan (ON) notifikasi terlebih dahulu.');
            return;
        }

        $user->notify(new WebPushGenericNotification(
            title: 'Uji Coba Web Push DCMS',
            body: 'Halo ' . $user->name . '! Notifikasi Web Push Anda telah aktif dan bekerja dengan sempurna.',
            url: '/admin',
            icon: asset('images/logo.png')
        ));
    }

    public function render()
    {
        return view('livewire.web-push-topbar-icon');
    }
}
