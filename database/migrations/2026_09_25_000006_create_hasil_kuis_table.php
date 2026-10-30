<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->unsignedInteger('nilai');
            $table->unsignedInteger('jumlah_benar');
            $table->unsignedInteger('jumlah_salah');
            $table->unsignedInteger('jumlah_soal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuis');
    }
};
