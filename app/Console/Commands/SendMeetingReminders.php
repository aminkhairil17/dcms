<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Notifications\MeetingReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendMeetingReminders extends Command
{
    protected $signature = 'meetings:send-reminders';

    protected $description = 'Mengirimkan notifikasi pengingat untuk rapat/meeting yang akan datang dalam 24 jam.';

    public function handle(): int
    {
        $upcomingMeetings = Meeting::query()
            ->where('status', 'scheduled')
            ->where('date_time', '>=', now())
            ->where('date_time', '<=', now()->addHours(24))
            ->where(function ($q) {
                $q->whereNull('reminder_sent_at')
                    ->orWhereDate('reminder_sent_at', '<', today());
            })
            ->with(['participants', 'creator'])
            ->get();

        $totalSent = 0;

        foreach ($upcomingMeetings as $meeting) {
            $participants = $meeting->participants;
            if ($meeting->creator && !$participants->contains('id', $meeting->created_by)) {
                $participants->push($meeting->creator);
            }

            if ($participants->isNotEmpty()) {
                try {
                    Notification::send($participants, new MeetingReminderNotification($meeting));
                    $meeting->update(['reminder_sent_at' => now()]);
                    $totalSent += $participants->count();
                } catch (\Throwable $e) {
                    Log::error("Failed sending meeting reminder for meeting {$meeting->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Notifikasi pengingat rapat terkirim ke {$totalSent} peserta.");

        return self::SUCCESS;
    }
}
