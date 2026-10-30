<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelajaran extends Model
{
    protected $table = 'pembelajaran';

    protected $fillable = ['user_id', 'nama', 'kode', 'deskripsi'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(PembelajaranAnggota::class, 'pembelajaran_id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'pembelajaran_anggota',
            'pembelajaran_id',
            'user_id'
        )->withTimestamps();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'pembelajaran_id');
    }

    public function hasilKuis(): HasMany
    {
        return $this->hasMany(HasilKuis::class, 'pembelajaran_id');
    }
}
