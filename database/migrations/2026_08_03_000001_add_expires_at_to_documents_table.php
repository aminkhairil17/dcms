<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->date('expires_at')->nullable()->after('version')
                ->comment('Tanggal kedaluwarsa dokumen — null = tidak ada batas waktu');
            $table->date('review_reminder_sent_at')->nullable()->after('expires_at')
                ->comment('Terakhir kali reminder kedaluwarsa dikirim');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'review_reminder_sent_at']);
        });
    }
};
