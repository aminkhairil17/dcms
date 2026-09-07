<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Kabid review tracking
            $table->foreignId('reviewed_by_kabid')->nullable()->after('approver_id')->constrained('users')->nullOnDelete();
            $table->timestamp('kabid_reviewed_at')->nullable()->after('reviewed_by_kabid');
            $table->text('kabid_notes')->nullable()->after('kabid_reviewed_at');

            // Direktur review tracking
            $table->foreignId('reviewed_by_direktur')->nullable()->after('kabid_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('direktur_reviewed_at')->nullable()->after('reviewed_by_direktur');
            $table->text('direktur_notes')->nullable()->after('direktur_reviewed_at');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE documents MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_kabid']);
            $table->dropForeign(['reviewed_by_direktur']);
            $table->dropColumn([
                'reviewed_by_kabid',
                'kabid_reviewed_at',
                'kabid_notes',
                'reviewed_by_direktur',
                'direktur_reviewed_at',
                'direktur_notes',
            ]);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('draft', 'pending_review', 'approved', 'rejected', 'archived') NOT NULL DEFAULT 'draft'");
        }
    }
};
