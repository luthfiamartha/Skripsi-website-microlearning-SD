<?php

namespace App\Http\Controllers\Concerns;

use App\Models\HasilKuis;
use App\Models\Materi;
use App\Models\Pembelajaran;
use App\Models\Progress;
use App\Models\User;

/**
 * Perhitungan progress untuk pemantauan guru/admin.
 * Konteks null = Belajar Mandiri, selain itu = Pembelajaran tertentu.
 */
trait MonitorsProgress
{
    /**
     * @param  \Illuminate\Support\Collection<int, User>  $siswaList
     * @return \Illuminate\Support\Collection<int, array>
     */
    protected function ringkasanSiswa($siswaList, ?Pembelajaran $pembelajaran, int $totalModul)
    {
        return $siswaList->map(function (User $siswa) use ($pembelajaran, $totalModul) {
            $progress = $this->padaKonteks(
                Progress::where('user_id', $siswa->id),
                $pembelajaran
            )->get();

            $modulSelesai = (int) $progress->sum('modul_selesai');

            return [
                'siswa' => $siswa,
                'modul_selesai' => $modulSelesai,
                'materi_selesai' => $progress->where('status', 'selesai')->count(),
                'persentase' => $totalModul > 0 ? round($modulSelesai / $totalModul * 100) : 0,
            ];
        });
    }

    /**
     * @return array{detailProgress: \Illuminate\Support\Collection, hasilSiswa: \Illuminate\Support\Collection}
     */
    protected function detailSiswa(User $siswa, $materi, ?Pembelajaran $pembelajaran): array
    {
        $progressMap = $this->padaKonteks(
            Progress::where('user_id', $siswa->id),
            $pembelajaran
        )->get()->keyBy('materi_id');

        $detailProgress = $materi->map(function ($m) use ($progressMap) {
            $p = $progressMap->get($m->id);

            return [
                'materi' => $m,
                'status' => $p?->status ?? 'belum',
                'persentase' => $p?->persentase ?? 0,
                'modul_selesai' => $p?->modul_selesai ?? 0,
            ];
        });

        $hasilSiswa = $this->padaKonteks(
            HasilKuis::where('user_id', $siswa->id),
            $pembelajaran
        )->with('kuis.materi')->latest()->get();

        return compact('detailProgress', 'hasilSiswa');
    }
}
