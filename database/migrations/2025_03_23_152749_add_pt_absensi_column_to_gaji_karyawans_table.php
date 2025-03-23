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
        Schema::table('gaji_karyawans', function (Blueprint $table) {
            $table->decimal('pt_absensi', 15, 2)->default(0)->after('pt_ll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaji_karyawans', function (Blueprint $table) {
            $table->dropColumn('pt_absensi');
        });
    }
};
