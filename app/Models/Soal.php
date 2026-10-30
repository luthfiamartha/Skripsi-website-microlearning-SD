<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'kuis_id', 'pertanyaan', 'pilihan_a', 'pilihan_b',
        'pilihan_c', 'pilihan_d', 'kunci_jawaban', 'urutan',
    ];

    public function kuis(): BelongsTo
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }
}
