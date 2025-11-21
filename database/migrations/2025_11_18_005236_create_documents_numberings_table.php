<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_numberings', function (Blueprint $table) {
            $table->id();

            // kategori dokumen (misal SOP, IK, FORM, dll)
            $table->unsignedBigInteger('category_id');

            // company & department (opsional dan boleh null)
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            // tahun penomoran (misal 2025)
            $table->year('year');

            // running number terakhir
            $table->integer('last_number')->default(0);

            $table->timestamps();

            // index untuk mempercepat query penomoran
            $table->unique(['category_id', 'company_id', 'department_id', 'year'], 'unique_doc_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_numberings');
    }
};
