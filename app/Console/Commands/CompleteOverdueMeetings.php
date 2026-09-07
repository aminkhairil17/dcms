<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use Illuminate\Console\Command;

class CompleteOverdueMeetings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'meetings:complete-overdue';

    /**
     * The console command description.
     */
    protected $description = 'Mengubah status rapat menjadi "completed" (Berakhir) apabila sudah melewati jam berakhir.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $updated = Meeting::where('status', 'scheduled')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('end_time')
                        ->where('end_time', '<=', now());
                })->orWhere(function ($q) {
                    $q->whereNull('end_time')
                        ->where('date_time', '<=', now());
                });
            })
            ->update(['status' => 'completed']);

        $this->info("Berhasil mengubah {$updated} rapat menjadi berakhir.");

        return self::SUCCESS;
    }
}
