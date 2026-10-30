<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SummarizesProgress;
use Illuminate\Http\Request;

class SiswaDashboardController extends Controller
{
    use SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        // Konteks: Belajar Mandiri
        $ringkasan = $this->ringkasanBelajar($siswa->id);
        $daftarKuis = $this->daftarKuis($siswa->id, $ringkasan);

        // Konteks: Pembelajaran dengan guru (hanya yang diikuti siswa ini)
        $pembelajaran = $siswa->pembelajaranDiikuti()
            ->with('guru')
            ->get()
            ->map(fn ($p) => [
                'pembelajaran' => $p,
                'ringkasan' => $this->ringkasanBelajar($siswa->id, $p),
            ]);

        return view('siswa.dashboard', compact(
            'siswa', 'ringkasan', 'daftarKuis', 'pembelajaran'
        ));
    }
}
