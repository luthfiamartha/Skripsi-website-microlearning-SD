<?php

namespace App\Http\Controllers\Concerns;

use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Pembelajaran;
use App\Models\Progress;

/**
 * Menghitung ringkasan belajar milik seorang siswa pada satu konteks
 * (Belajar Mandiri = null, atau salah satu Pembelajaran Guru).
 */
trait SummarizesProgress
{
    /**
     * @return array{
     *     daftarMateri: \Illuminate\Support\Collection,
     *     totalModul: int, modulSelesai: int, persentase: int,
     *     materiSedang: ?Materi, materiDipelajari: int, materiSelesai: int,
     *     kuisDikerjakan: int, hasilTerbaru: \Illuminate\Support\Collection
     * }
     */
    protected function ringkasanBelajar(int $userId, ?Pembelajaran $pembelajaran = null): array
    {
        $materi = Materi::withCount('modul')->orderBy('id')->get();

        $progress = Progress::where('user_id', $userId)
            ->when(
                $pembelajaran,
                fn ($q) => $q->where('pembelajaran_id', $pembelajaran->id),
                fn ($q) => $q->whereNull('pembelajaran_id')
            )
            ->get()
            ->keyBy('materi_id');

        $totalModul = (int) $materi->sum('modul_count');
        $modulSelesai = (int) $progress->sum('modul_selesai');
        $persentase = $totalModul > 0 ? round($modulSelesai / $totalModul * 100) : 0;

        $daftarMateri = $materi->map(function ($m) use ($progress) {
            $p = $progress->get($m->id);

            return [
                'materi' => $m,
                'status' => $p?->status ?? 'belum',
                'persentase' => (int) ($p?->persentase ?? 0),
                'modul_selesai' => (int) ($p?->modul_selesai ?? 0),
                'modul_count' => (int) $m->modul_count,
            ];
        });

        $materiDipelajari = $daftarMateri->filter(fn ($i) => $i['persentase'] > 0)->count();
        $materiSelesai = $daftarMateri->filter(fn ($i) => $i['status'] === 'selesai')->count();

        $materiSedang = $daftarMateri
            ->first(fn ($i) => $i['status'] !== 'selesai' && $i['persentase'] > 0)['materi']
            ?? $daftarMateri->firstWhere('status', '!=', 'selesai')['materi']
            ?? null;

        $hasilTerbaru = HasilKuis::with('kuis.materi')
            ->where('user_id', $userId)
            ->when(
                $pembelajaran,
                fn ($q) => $q->where('pembelajaran_id', $pembelajaran->id),
                fn ($q) => $q->whereNull('pembelajaran_id')
            )
            ->latest()
            ->limit(3)
            ->get();

        $kuisDikerjakan = HasilKuis::where('user_id', $userId)
            ->when(
                $pembelajaran,
                fn ($q) => $q->where('pembelajaran_id', $pembelajaran->id),
                fn ($q) => $q->whereNull('pembelajaran_id')
            )
            ->count();

        return [
            'daftarMateri' => $daftarMateri,
            'totalModul' => $totalModul,
            'modulSelesai' => $modulSelesai,
            'persentase' => $persentase,
            'materiSedang' => $materiSedang,
            'materiDipelajari' => $materiDipelajari,
            'materiSelesai' => $materiSelesai,
            'kuisDikerjakan' => $kuisDikerjakan,
            'hasilTerbaru' => $hasilTerbaru,
        ];
    }

    /**
     * Daftar kuis beserta statusnya pada konteks yang sedang dibuka.
     *
     * @return \Illuminate\Support\Collection<int, array{
     *     kuis: \App\Models\Kuis, materi: ?\App\Models\Materi,
     *     jumlah_soal: int, status: string, nilai: ?int
     * }>
     */
    protected function daftarKuis(int $userId, array $ringkasan, ?Pembelajaran $pembelajaran = null)
    {
        $materiById = collect($ringkasan['daftarMateri'])->keyBy(fn ($i) => $i['materi']->id);

        $nilaiTerakhir = HasilKuis::where('user_id', $userId)
            ->when(
                $pembelajaran,
                fn ($q) => $q->where('pembelajaran_id', $pembelajaran->id),
                fn ($q) => $q->whereNull('pembelajaran_id')
            )
            ->latest()
            ->get()
            ->groupBy('kuis_id')
            ->map(fn ($grup) => $grup->first());

        return Kuis::with('materi')->withCount('soal')->orderBy('id')->get()
            ->map(function ($kuis) use ($materiById, $nilaiTerakhir) {
                $item = $materiById->get($kuis->materi_id);
                $selesai = $item['status'] === 'selesai';
                $hasil = $nilaiTerakhir->get($kuis->id);

                return [
                    'kuis' => $kuis,
                    'materi' => $kuis->materi,
                    'jumlah_soal' => (int) $kuis->soal_count,
                    'status' => $selesai ? 'siap' : 'terkunci',
                    'nilai' => $hasil?->nilai,
                ];
            });
    }
}
