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
       Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('document_number')->unique()->nullable();
            $table->text('description')->nullable();
            
            // File information
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_type');
            $table->string('file_extension');
            $table->string('version')->default('1.0');
            
            // Organizational hierarchy
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            
            // Classification
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Uploader
            
            // Metadata & Status
            $table->date('effective_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('confidential_level', ['public', 'internal', 'confidential', 'secret'])->default('internal');
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'archived'])->default('draft');
            
            // Version control & Archiving
            $table->foreignId('previous_version_id')->nullable()->constrained('documents')->onDelete('set null');
            $table->boolean('is_current_version')->default(true);
            $table->text('revision_notes')->nullable(); // Catatan revisi
            
            // Approval simple
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Untuk arsip
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
