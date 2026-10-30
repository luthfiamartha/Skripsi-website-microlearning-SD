<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->string('kode', 20)->unique();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('pembelajaran_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelajaran_id')->constrained('pembelajaran')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pembelajaran_id', 'user_id']);
        });

        Schema::table('progress', function (Blueprint $table) {
            $table->foreignId('pembelajaran_id')
                ->nullable()
                ->after('materi_id')
                ->constrained('pembelajaran')
                ->nullOnDelete();

            // Index baru dibuat lebih dulu agar tetap menopang foreign key user_id,
            // baru index unique lama (user_id, materi_id) dihapus.
            $table->unique(['user_id', 'materi_id', 'pembelajaran_id']);
            $table->dropUnique(['user_id', 'materi_id']);
        });

        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->foreignId('pembelajaran_id')
                ->nullable()
                ->after('kuis_id')
                ->constrained('pembelajaran')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hasil_kuis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pembelajaran_id');
        });

        Schema::table('progress', function (Blueprint $table) {
            $table->unique(['user_id', 'materi_id']);
            $table->dropUnique(['user_id', 'materi_id', 'pembelajaran_id']);
            $table->dropForeign(['pembelajaran_id']);
            $table->dropColumn('pembelajaran_id');
        });

        Schema::dropIfExists('pembelajaran_anggota');
        Schema::dropIfExists('pembelajaran');
    }
};
