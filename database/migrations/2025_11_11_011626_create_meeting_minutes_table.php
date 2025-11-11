<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('meeting_minutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->longText('content'); // Untuk notulensi dengan tagging
            $table->json('action_items')->nullable(); // Menyimpan action items sebagai JSON
            $table->json('decisions')->nullable(); // Menyimpan keputusan sebagai JSON
            $table->json('mentioned_users')->nullable(); // Menyimpan ID user yang di-tag
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_minutes');
    }
};
