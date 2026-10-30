<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = ['judul', 'deskripsi'];

    public function modul(): HasMany
    {
        return $this->hasMany(Modul::class, 'materi_id')->orderBy('urutan');
    }

    public function kuis(): HasOne
    {
        return $this->hasOne(Kuis::class, 'materi_id');
    }
}
