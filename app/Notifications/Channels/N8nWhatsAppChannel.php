<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * N8nWhatsAppChannel
 *
 * Custom notification channel yang mengirim notifikasi DCMS ke n8n
 * melalui sebuah Webhook (HTTP POST). Di dalam n8n, payload ini
 * kemudian diteruskan ke node WhatsApp untuk menghasilkan pop-up di HP.
 *
 * Alur:
 *   Notification (via 'n8n') -> N8nWhatsAppChannel -> POST config('services.n8n.webhook_url')
 *   -> n8n Webhook -> WhatsApp node -> HP user
 *
 * Notifikasi yang ingin dikirim ke WhatsApp WAJIB memiliki method toN8n($notifiable)
 * yang mengembalikan array (minimal berisi 'message').
 */
class N8nWhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // Notifikasi harus menyediakan data lewat toN8n().
        if (! method_exists($notification, 'toN8n')) {
            return;
        }

        $webhookUrl = config('services.n8n.webhook_url');

        if (empty($webhookUrl)) {
            Log::warning('N8nWhatsAppChannel: services.n8n.webhook_url belum diset, notifikasi WhatsApp dilewati.');
            return;
        }

        // Nomor WhatsApp tujuan diambil dari routeNotificationFor('n8n') / 'whatsapp'
        // yang biasanya mengembalikan kolom users.phone.
        $to = $notifiable->routeNotificationFor('n8n', $notification)
            ?? $notifiable->routeNotificationFor('whatsapp', $notification);

        // Tanpa nomor tujuan, tidak ada yang bisa dikirim.
        if (empty($to)) {
            return;
        }

        /** @var array $data */
        $data = $notification->toN8n($notifiable);

        $payload = array_merge([
            'to'        => $this->normalizePhone((string) $to),
            'name'      => $notifiable->name ?? null,
            'channel'   => 'whatsapp',
            'source'    => 'dcms',
        ], $data);

        try {
            $request = Http::asJson()->timeout(15);

            // Secret opsional untuk mengamankan webhook n8n.
            $secret = config('services.n8n.secret');
            if (! empty($secret)) {
                $request = $request->withHeaders(['X-DCMS-Signature' => $secret]);
            }

            $response = $request->post($webhookUrl, $payload);

            if ($response->failed()) {
                Log::error('N8nWhatsAppChannel: webhook n8n mengembalikan error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('N8nWhatsAppChannel: gagal mengirim ke webhook n8n: ' . $e->getMessage());
        }
    }

    /**
     * Normalisasi nomor ke format internasional tanpa "+", contoh: 6281234567890.
     * Mengubah awalan 0 (nomor Indonesia) menjadi 62.
     */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits;
    }
}
