<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentExpiryReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDocumentExpiryReminders extends Command
{
    protected $signature = 'documents:send-expiry-reminders';

    protected $description = 'Mengirim notifikasi pengingat untuk dokumen yang akan kedaluwarsa dalam 30 hari dan 7 hari.';

    public function handle(): int
    {
        $totalSent = 0;

        foreach ([30, 7] as $daysLeft) {
            $documents = Document::query()
                ->where('status', Document::STATUS_APPROVED)
                ->whereNotNull('expires_at')
                ->whereDate('expires_at', today()->addDays($daysLeft))
                ->where(function ($query) {
                    $query->whereNull('review_reminder_sent_at')
                        ->orWhereDate('review_reminder_sent_at', '<', today());
                })
                ->with(['user', 'department'])
                ->get();

            foreach ($documents as $document) {
                $recipients = $this->resolveRecipients($document);

                if ($recipients->isEmpty()) {
                    continue;
                }

                Notification::send($recipients, new DocumentExpiryReminderNotification($document, $daysLeft));

                $document->forceFill([
                    'review_reminder_sent_at' => today(),
                ])->saveQuietly();

                $totalSent += $recipients->count();
            }
        }

        $this->info("Pengingat masa berlaku terkirim ke {$totalSent} penerima.");

        return self::SUCCESS;
    }

    protected function resolveRecipients(Document $document)
    {
        $ownerId = $document->user_id;
        $departmentId = $document->department_id;
        $companyId = $document->company_id;

        $recipientIds = collect();

        if ($ownerId) {
            $recipientIds->push($ownerId);
        }

        if ($departmentId) {
            $recipientIds = $recipientIds->merge(
                User::role(['kabid', 'manager'])
                    ->where('department_id', $departmentId)
                    ->pluck('id'),
            );
        }

        if ($companyId) {
            $recipientIds = $recipientIds->merge(
                User::role(['direktur', 'super_admin'])
                    ->where('company_id', $companyId)
                    ->pluck('id'),
            );
        }

        $recipientIds = $recipientIds->filter()->unique()->values();

        return $recipientIds->isEmpty()
            ? collect()
            : User::query()->whereIn('id', $recipientIds)->get();
    }
}
