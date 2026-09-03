<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom `phone` (nomor WhatsApp) ke tabel users.
 *
 * Nomor disimpan dalam format internasional tanpa tanda "+", contoh: 6281234567890.
 * Kolom ini dipakai oleh channel notifikasi WhatsApp (n8n) untuk menentukan
 * ke nomor mana notifikasi DCMS dikirim.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
