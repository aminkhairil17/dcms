<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->foreignId('meeting_location_id')
                ->nullable()
                ->after('location')
                ->constrained('meeting_locations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['meeting_location_id']);
            $table->dropColumn('meeting_location_id');
        });
    }
};
