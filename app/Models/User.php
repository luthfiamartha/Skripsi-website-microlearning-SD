<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'guru' => '/guru/dashboard',
            'admin' => '/admin/dashboard',
            default => '/siswa/dashboard',
        };
    }

    public function pembelajaranDibuat(): HasMany
    {
        return $this->hasMany(Pembelajaran::class, 'user_id');
    }

    public function pembelajaranDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(
            Pembelajaran::class,
            'pembelajaran_anggota',
            'user_id',
            'pembelajaran_id'
        )->withTimestamps()->orderBy('pembelajaran.nama');
    }

    public function jumlahPembelajaranDiikuti(): int
    {
        return $this->pembelajaranDiikuti()->count();
    }
}
