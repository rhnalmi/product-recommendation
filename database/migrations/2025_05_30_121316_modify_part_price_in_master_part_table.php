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
       Schema::table('master_part', function (Blueprint $table) {
            // Pilih salah satu:
            // $table->decimal('part_price', 10, 2)->default(0.00)->change(); // Untuk NOT NULL DEFAULT 0.00
            $table->decimal('part_price', 10, 2)->nullable()->change(); // Untuk NULLABLE
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_part', function (Blueprint $table) {
            // Sesuaikan DDL rollback jika perlu, contoh:
            $table->decimal('part_price', 10, 2)->nullable(false)->change();
        });
    }
};
