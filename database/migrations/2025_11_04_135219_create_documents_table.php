<?php
// database/migrations/2024_01_01_500000_create_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // File info
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('version')->default('1.0');

            // ORGANIZATIONAL HIERARCHY (CORE)
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Status & Metadata
            $table->enum('status', ['draft', 'pending_review', 'approved', 'rejected', 'archived'])->default('draft');
            $table->enum('confidential_level', ['public', 'internal', 'confidential'])->default('internal');

            // Future proof fields
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
