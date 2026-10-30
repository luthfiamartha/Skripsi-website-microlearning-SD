<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Models\Materi;
use App\Models\Progress;
use Illuminate\Http\Request;

class SiswaMateriController extends Controller
{
    use ResolvesPembelajaran;

    public function index(Request $request)
    {
        $siswa = $request->user();
        $pembelajaran = $this->pembelajaranSiswa($request);

        $materi = Materi::withCount('modul')->orderBy('id')->get();

        $progress = $this->padaKonteks(
            Progress::where('user_id', $siswa->id),
            $pembelajaran
        )->get()->keyBy('materi_id');

        $daftarMateri = $materi->map(function ($m) use ($progress) {
            $p = $progress->get($m->id);

            return [
                'materi' => $m,
                'status' => $p?->status ?? 'belum',
                'persentase' => $p?->persentase ?? 0,
                'modul_selesai' => $p?->modul_selesai ?? 0,
            ];
        });

        return view('siswa.materi.index', [
            'siswa' => $siswa,
            'daftarMateri' => $daftarMateri,
            'pembelajaran' => $pembelajaran,
        ]);
    }

    public function show(Request $request, Materi $materi)
    {
        $siswa = $request->user();
        $pembelajaran = $this->pembelajaranSiswa($request);

        $modulList = $materi->modul()->get();

        if ($modulList->isEmpty()) {
            return view('siswa.materi.show', [
                'siswa' => $siswa,
                'materi' => $materi,
                'pembelajaran' => $pembelajaran,
                'modulList' => $modulList,
                'modulAktif' => null,
                'indexAktif' => 0,
                'progress' => null,
                'totalModul' => 0,
                'kuis' => null,
            ]);
        }

        $progress = Progress::firstOrCreate(
            [
                'user_id' => $siswa->id,
                'materi_id' => $materi->id,
                'pembelajaran_id' => $pembelajaran?->id,
            ],
            ['status' => 'belum', 'modul_selesai' => 0, 'persentase' => 0]
        );

        $totalModul = $modulList->count();
        $batas = min($progress->modul_selesai, $totalModul) + 1;

        $modulId = $request->query('modul');
        $modulAktif = $modulId ? $modulList->firstWhere('id', $modulId) : null;

        if (! $modulAktif || $modulAktif->urutan > $batas) {
            $urutanTujuan = $progress->modul_selesai >= $totalModul ? $totalModul : $batas;
            $modulAktif = $modulList->firstWhere('urutan', $urutanTujuan) ?? $modulList->first();
        }

        $indexAktif = $modulList->search(fn ($m) => $m->id === $modulAktif->id);
        $kuis = $materi->kuis;

        return view('siswa.materi.show', compact(
            'siswa', 'materi', 'pembelajaran', 'modulList', 'modulAktif', 'indexAktif', 'progress', 'totalModul', 'kuis'
        ));
    }

    public function selesaiModul(Request $request, Materi $materi)
    {
        $siswa = $request->user();
        $pembelajaran = $this->pembelajaranSiswa($request);

        $request->validate([
            'modul_id' => ['required', 'integer', 'exists:modul,id'],
        ]);

        $modulList = $materi->modul()->get();

        if ($modulList->isEmpty()) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Materi ini belum memiliki modul.');
        }

        $modul = $modulList->firstWhere('id', $request->modul_id);

        if (! $modul) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Modul tidak ditemukan.');
        }

        $progress = Progress::firstOrCreate(
            [
                'user_id' => $siswa->id,
                'materi_id' => $materi->id,
                'pembelajaran_id' => $pembelajaran?->id,
            ],
            ['status' => 'belum', 'modul_selesai' => 0, 'persentase' => 0]
        );

        $totalModul = $modulList->count();
        $batas = min($progress->modul_selesai, $totalModul) + 1;

        if ($modul->urutan > $batas) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Selesaikan modul sebelumnya terlebih dahulu.');
        }

        if ($modul->urutan == $progress->modul_selesai + 1) {
            $progress->modul_selesai = $modul->urutan;
        }

        $selesai = min($progress->modul_selesai, $totalModul);
        $progress->persentase = $totalModul > 0 ? round($selesai / $totalModul * 100) : 0;
        $progress->status = ($totalModul > 0 && $selesai >= $totalModul) ? 'selesai' : 'belum';
        $progress->save();

        $pesan = $progress->status === 'selesai'
            ? 'Seluruh modul materi selesai. Silakan kerjakan kuis.'
            : 'Modul ditandai selesai. Lanjut ke modul berikutnya.';

        if ($modul->urutan < $totalModul) {
            $berikutnya = $modulList->firstWhere('urutan', $modul->urutan + 1);

            return redirect()->route('siswa.materi.show', array_merge(
                [$materi, 'modul' => $berikutnya->id],
                $this->paramKonteks($pembelajaran)
            ))->with('sukses', $pesan);
        }

        return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
            ->with('sukses', $pesan);
    }
}
