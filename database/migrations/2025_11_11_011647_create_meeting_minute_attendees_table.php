<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_minute_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_minute_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('attended')->default(false);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['meeting_minute_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_minute_attendees');
    }
};