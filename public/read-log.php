<?php

/**
 * Script untuk membaca 50 baris terakhir log Laravel
 */

// Proteksi
$key = $_GET['key'] ?? '';
if ($key !== 'ceklog2024') {
    exit('Unauthorized.');
}

$logFile = __DIR__.'/../storage/logs/laravel.log';

if (file_exists($logFile)) {
    $file = file($logFile);
    $lines = array_slice($file, -50);
    echo '<h3>50 Baris Terakhir Log PHP/Laravel:</h3><pre>';
    foreach ($lines as $line) {
        echo htmlspecialchars($line);
    }
    echo '</pre>';
} else {
    echo '❌ File log tidak ditemukan di: '.$logFile;
}
