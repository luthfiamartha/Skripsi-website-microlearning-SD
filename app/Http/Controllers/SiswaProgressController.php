<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Http\Controllers\Concerns\SummarizesProgress;
use Illuminate\Http\Request;

class SiswaProgressController extends Controller
{
    use ResolvesPembelajaran, SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $pilihan = collect([
            [
                'label' => 'Belajar Mandiri',
                'emoji' => '🌱',
                'pembelajaran' => null,
                'ringkasan' => $this->ringkasanBelajar($siswa->id),
            ],
        ]);

        $siswa->pembelajaranDiikuti()
            ->with('guru')
            ->get()
            ->each(function ($pembelajaran) use ($siswa, $pilihan) {
                $pilihan->push([
                    'label' => 'Pembelajaran ' . $pembelajaran->nama,
                    'emoji' => '👩‍🏫',
                    'pembelajaran' => $pembelajaran,
                    'ringkasan' => $this->ringkasanBelajar($siswa->id, $pembelajaran),
                ]);
            });

        // Daftar kuis (dengan nilai) untuk tiap konteks — tetap terpisah.
        $pilihan = $pilihan->map(function ($item) use ($siswa) {
            $item['kuis'] = $this->daftarKuis($siswa->id, $item['ringkasan'], $item['pembelajaran']);
            $item['kuisSelesai'] = $item['kuis']->filter(fn ($k) => $k['nilai'] !== null)->count();

            return $item;
        });

        // Konteks aktif: null = Belajar Mandiri, selain itu wajib anggota (dicek backend)
        $pembelajaran = $this->pembelajaranSiswa($request);

        $aktif = $pilihan->first(
            fn ($item) => $item['pembelajaran']?->id === $pembelajaran?->id
        );

        return view('siswa.progress.index', [
            'siswa' => $siswa,
            'pilihan' => $pilihan,
            'aktif' => $aktif,
            'pembelajaran' => $pembelajaran,
        ]);
    }
}
