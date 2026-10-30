<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Pembelajaran;
use Illuminate\Http\Request;

/**
 * Konteks pembelajaran siswa:
 * - tanpa ?pembelajaran=  -> Belajar Mandiri (pembelajaran_id = NULL)
 * - dengan ?pembelajaran= -> Pembelajaran Guru (wajib anggota, diperiksa backend)
 */
trait ResolvesPembelajaran
{
    protected function pembelajaranSiswa(Request $request): ?Pembelajaran
    {
        $siswa = $request->user();

        if (! $siswa || $siswa->role !== 'siswa') {
            abort(403, 'Halaman ini hanya untuk siswa.');
        }

        $id = $request->query('pembelajaran');

        if ($id === null || $id === '') {
            return null;
        }

        if (! ctype_digit((string) $id)) {
            abort(404, 'Pembelajaran tidak ditemukan.');
        }

        $pembelajaran = Pembelajaran::find((int) $id);

        if (! $pembelajaran) {
            abort(404, 'Pembelajaran tidak ditemukan.');
        }

        $sudahGabung = $pembelajaran->anggota()
            ->where('user_id', $siswa->id)
            ->exists();

        if (! $sudahGabung) {
            abort(403, 'Kamu belum bergabung ke pembelajaran ini.');
        }

        return $pembelajaran;
    }

    /**
     * Query dasar progress/hasil milik siswa pada konteks yang sedang dibuka.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function padaKonteks($query, ?Pembelajaran $pembelajaran, string $kolom = 'pembelajaran_id')
    {
        return $pembelajaran
            ? $query->where($kolom, $pembelajaran->id)
            : $query->whereNull($kolom);
    }

    protected function paramKonteks(?Pembelajaran $pembelajaran): array
    {
        return $pembelajaran ? ['pembelajaran' => $pembelajaran->id] : [];
    }
}
