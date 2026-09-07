<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Insert kategori "Dokumen"
$exists = DB::table('document_categories')->where('name', 'Dokumen')->first();
if ($exists) {
    echo "Kategori 'Dokumen' sudah ada (ID: {$exists->id})\n";
} else {
    $id = DB::table('document_categories')->insertGetId([
        'name' => 'Dokumen',
        'description' => 'Kategori umum untuk dokumen',
        'prefix' => 'DOC',
        'color' => '#3b82f6',
        'requires_approval' => false,
        'is_active' => true,
        'icon' => 'heroicon-o-document-text',
        'sort_order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Kategori 'Dokumen' berhasil dibuat (ID: {$id})\n";
}
