<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SummarizesProgress;
use App\Models\Kuis;
use App\Models\Modul;
use Illuminate\Http\Request;

class SiswaMandiriController extends Controller
{
    use SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $ringkasan = $this->ringkasanBelajar($siswa->id);
        $daftarKuis = $this->daftarKuis($siswa->id, $ringkasan);

        return view('siswa.mandiri.index', [
            'siswa' => $siswa,
            'ringkasan' => $ringkasan,
            'daftarKuis' => $daftarKuis,
            'jumlahMateri' => $ringkasan['daftarMateri']->count(),
            'jumlahModul' => Modul::count(),
            'jumlahKuis' => Kuis::count(),
        ]);
    }
}
