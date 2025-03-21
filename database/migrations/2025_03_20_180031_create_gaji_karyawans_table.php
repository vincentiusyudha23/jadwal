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
        Schema::create('gaji_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('total_hari_kerja')->default(0)->nullable();
            $table->integer('ijin')->default(0)->nullable();
            $table->integer('cuti')->default(0)->nullable();
            $table->integer('sakit')->default(0)->nullable();
            $table->integer('alpa')->default(0)->nullable();
            $table->integer('total_absen')->default(0)->nullable();
            $table->decimal('gp_bulanan', 15, 2)->default(0)->nullable();
            $table->decimal('tj_komunikasi', 15, 2)->default(0)->nullable();
            $table->decimal('tj_keahlian', 15, 2)->default(0)->nullable();
            $table->decimal('tj_kesehatan', 15, 2)->default(0)->nullable();
            $table->decimal('total_upah_tetap', 15, 2)->default(0)->nullable();
            $table->decimal('tj_makan', 15, 2)->default(0)->nullable();
            $table->decimal('lembur', 15, 2)->default(0)->nullable();
            $table->decimal('tj_transport', 15, 2)->default(0)->nullable();
            $table->decimal('pll', 15, 2)->default(0)->nullable();
            $table->decimal('pp', 15, 2)->default(0)->nullable();
            $table->decimal('lbpph21', 15, 2)->default(0)->nullable();
            $table->decimal('total_upah_non_tetap', 15, 2)->default(0)->nullable();
            $table->decimal('pt_pph21', 15, 2)->default(0)->nullable();
            $table->decimal('pt_pp', 15, 2)->default(0)->nullable();
            $table->decimal('pt_bpjs_kesehatan', 15, 2)->default(0)->nullable();
            $table->decimal('pt_bpjs_kerja', 15, 2)->default(0)->nullable();
            $table->decimal('pt_ll', 15, 2)->default(0)->nullable();
            $table->decimal('total_potongan', 15, 2)->default(0)->nullable();
            $table->decimal('total_diterima', 15, 2)->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_karyawans');
    }
};
