<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->string('color')->default('#3b82f6');
            $table->json('allowed_extensions')->nullable(); // ['pdf', 'doc', 'docx']
            $table->integer('max_file_size')->default(10); // dalam MB
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // Kategori spesifik per perusahaan
            $table->boolean('requires_approval')->default(false); // Butuh approval untuk kategori ini?
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['code', 'company_id']); // Code unique per perusahaan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};
