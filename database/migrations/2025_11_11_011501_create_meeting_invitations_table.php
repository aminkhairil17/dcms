<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Invitation status
            $table->enum('status', ['pending', 'accepted', 'declined', 'tentative'])->default('pending');
            $table->text('response_note')->nullable();
            $table->timestamp('responded_at')->nullable();

            // Email tracking
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();

            $table->timestamps();

            // Unique constraint - satu user hanya bisa diundang sekali per meeting
            $table->unique(['meeting_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_invitations');
    }
};
