<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_minutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->text('agenda');
            $table->text('discussion_points')->nullable();
            $table->text('decisions')->nullable();
            $table->text('action_items')->nullable();
            $table->text('next_meeting_agenda')->nullable();
            
            // Notulen info
            $table->foreignId('minute_taker_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('minute_date');
            
            // Approval
            $table->enum('status', ['draft', 'review', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_minutes');
    }
};