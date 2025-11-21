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
        Schema::table('documents', function (Blueprint $table) {
            // Hapus kolom yang tidak ada di list
            $table->dropColumn([
                'file_size',        // Tidak ada di list
                'sequence',         // Tidak ada di list  
                'confidential_level' // Tidak ada di list
            ]);

            // Tambahkan kolom yang ada di list tapi mungkin belum ada
            if (!Schema::hasColumn('documents', 'document_type')) {
                $table->string('document_type')->nullable()->after('content');
            }

            if (!Schema::hasColumn('documents', 'generated_file_path')) {
                $table->string('generated_file_path')->nullable()->after('document_type');
            }

            if (!Schema::hasColumn('documents', 'unit_id')) {
                $table->foreignId('unit_id')->nullable()->after('department_id')->constrained()->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->integer('file_size')->nullable();
            $table->integer('sequence')->default(0);
            $table->string('confidential_level')->default('low');

            // Hapus kolom yang ditambahkan
            $table->dropColumn(['document_type', 'generated_file_path', 'unit_id']);
        });
    }
};
