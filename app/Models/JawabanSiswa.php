<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';

    protected $fillable = ['hasil_kuis_id', 'soal_id', 'jawaban', 'benar'];

    protected $casts = ['benar' => 'boolean'];

    public function hasilKuis(): BelongsTo
    {
        return $this->belongsTo(HasilKuis::class, 'hasil_kuis_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
