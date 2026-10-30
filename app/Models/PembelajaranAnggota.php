<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelajaranAnggota extends Model
{
    protected $table = 'pembelajaran_anggota';

    protected $fillable = ['pembelajaran_id', 'user_id'];

    public function pembelajaran(): BelongsTo
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
