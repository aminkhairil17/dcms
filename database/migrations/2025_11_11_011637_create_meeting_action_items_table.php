<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_minute_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->date('due_date');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('completion_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_action_items');
    }
};