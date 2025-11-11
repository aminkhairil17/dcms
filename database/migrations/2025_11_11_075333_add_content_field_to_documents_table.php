<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('document_type', ['file', 'form'])->default('file');
            $table->text('content')->nullable(); // Untuk dokumen yang dibuat dari form
            $table->string('generated_file_path')->nullable(); // Path file yang digenerate dari content
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'content', 'generated_file_path']);
        });
    }
};
