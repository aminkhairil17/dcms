<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('meeting_date');
            $table->string('location')->nullable();
            $table->string('meeting_code')->unique();
            
            // Organizational
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Status
            $table->enum('status', ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->enum('meeting_type', ['regular', 'urgent', 'planning', 'review'])->default('regular');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};