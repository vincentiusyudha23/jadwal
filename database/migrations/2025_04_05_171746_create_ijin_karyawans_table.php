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
        Schema::create('ijin_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->references('id')->on('users')->cascadeOnDelete();
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('type', [1,2,3]);
            $table->text('keterangan');
            $table->string('surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ijin_karyawans');
    }
};
