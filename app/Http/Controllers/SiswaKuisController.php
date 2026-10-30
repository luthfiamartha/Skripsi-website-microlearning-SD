<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Http\Controllers\Concerns\SummarizesProgress;
use App\Models\HasilKuis;
use App\Models\JawabanSiswa;
use App\Models\Materi;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaKuisController extends Controller
{
    use ResolvesPembelajaran, SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $ringkasan = $this->ringkasanBelajar($siswa->id);
        $kuisMandiri = $this->daftarKuis($siswa->id, $ringkasan);

        $pembelajaran = $siswa->pembelajaranDiikuti()
            ->with('guru')
            ->get()
            ->map(fn ($p) => [
                'pembelajaran' => $p,
                'kuis' => $this->daftarKuis($siswa->id, $this->ringkasanBelajar($siswa->id, $p), $p),
            ]);

        return view('siswa.kuis.index', compact('siswa', 'kuisMandiri', 'pembelajaran'));
    }

    public function show(Request $request, Materi $materi)
    {
        $siswa = $request->user();
        $pembelajaran = $this->pembelajaranSiswa($request);

        $kuis = $materi->kuis()->with('soal')->first();

        if (! $kuis) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Kuis belum tersedia untuk materi ini.');
        }

        $progress = $this->padaKonteks(
            Progress::where('user_id', $siswa->id)->where('materi_id', $materi->id),
            $pembelajaran
        )->first();

        if (! $progress || $progress->status !== 'selesai') {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Selesaikan seluruh modul materi sebelum mengerjakan kuis.');
        }

        if ($kuis->soal->isEmpty()) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Kuis belum tersedia untuk materi ini.');
        }

        return view('siswa.kuis.show', compact('siswa', 'materi', 'pembelajaran', 'kuis'));
    }

    public function submit(Request $request, Materi $materi)
    {
        $siswa = $request->user();
        $pembelajaran = $this->pembelajaranSiswa($request);

        $kuis = $materi->kuis()->with('soal')->first();

        if (! $kuis || $kuis->soal->isEmpty()) {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Kuis belum tersedia untuk materi ini.');
        }

        $progress = $this->padaKonteks(
            Progress::where('user_id', $siswa->id)->where('materi_id', $materi->id),
            $pembelajaran
        )->first();

        if (! $progress || $progress->status !== 'selesai') {
            return redirect()->route('siswa.materi.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Selesaikan seluruh modul materi sebelum mengerjakan kuis.');
        }

        $jumlahSoal = $kuis->soal->count();

        $request->validate([
            'jawaban' => ['required', 'array', 'size:'.$jumlahSoal],
            'jawaban.*' => ['required', 'in:a,b,c,d'],
        ]);

        $jawaban = $request->jawaban;
        $jumlahBenar = 0;

        foreach ($kuis->soal as $soal) {
            if (($jawaban[$soal->id] ?? null) === $soal->kunci_jawaban) {
                $jumlahBenar++;
            }
        }

        $jumlahSalah = $jumlahSoal - $jumlahBenar;
        $nilai = $jumlahSoal > 0 ? round($jumlahBenar / $jumlahSoal * 100) : 0;

        try {
            DB::beginTransaction();

            $hasil = HasilKuis::create([
                'user_id' => $siswa->id,
                'kuis_id' => $kuis->id,
                'pembelajaran_id' => $pembelajaran?->id,
                'nilai' => $nilai,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'jumlah_soal' => $jumlahSoal,
            ]);

            foreach ($kuis->soal as $soal) {
                $jawabanSiswa = $jawaban[$soal->id];

                JawabanSiswa::create([
                    'hasil_kuis_id' => $hasil->id,
                    'soal_id' => $soal->id,
                    'jawaban' => $jawabanSiswa,
                    'benar' => $jawabanSiswa === $soal->kunci_jawaban,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('siswa.kuis.show', array_merge([$materi], $this->paramKonteks($pembelajaran)))
                ->with('error', 'Gagal menyimpan hasil kuis. Silakan coba lagi.');
        }

        return redirect()->route('siswa.hasil.show', $hasil)
            ->with('sukses', 'Kuis selesai dikerjakan.');
    }
}
