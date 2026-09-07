<?php

/**
 * File ini untuk membersihkan tabel session di database jika kamu
 * stuck di login loop.
 */

// Proteksi sederhana
$key = $_GET['key'] ?? '';
if ($key !== 'resetdcms2024') {
    exit('Unauthorized. Tambahkan ?key=resetdcms2024 di URL.');
}

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    if (Schema::hasTable('sessions')) {
        DB::table('sessions')->truncate(); // Kosongkan semua antrean login
        echo "✅ Tabel 'sessions' berhasil dikosongkan. Silakan coba login lagi!";
    } else {
        echo "⚠️ Tabel 'sessions' tidak ditemukan. Mungkin Anda pakai driver 'file'.";
    }

    // Paksa clear lagi
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('optimize:clear');
    echo '<br>✅ Semua cache sistem dibersihkan!';

} catch (\Exception $e) {
    echo '❌ Error: '.$e->getMessage();
}
