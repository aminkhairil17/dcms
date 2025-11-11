<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->text('template_content'); // HTML/content template dengan placeholder
            $table->json('template_fields'); // Field-field yang bisa diisi [{name: 'title', type: 'text', required: true}]

            // Organizational
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('cascade');

            // Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_file_upload')->default(true);
            $table->boolean('allow_manual_input')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
