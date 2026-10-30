<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SummarizesProgress;
use App\Models\HasilKuis;
use Illuminate\Http\Request;

class SiswaHasilKuisController extends Controller
{
    use SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $hasil = HasilKuis::with(['kuis.materi', 'pembelajaran'])
            ->where('user_id', $siswa->id)
            ->latest()
            ->get();

        return view('siswa.hasil_kuis.index', compact('siswa', 'hasil'));
    }

    public function show(Request $request, HasilKuis $hasil)
    {
        $siswa = $request->user();

        if ($hasil->user_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke hasil kuis ini.');
        }

        $hasil->load(['kuis.materi', 'pembelajaran']);

        return view('siswa.hasil_kuis.show', compact('siswa', 'hasil'));
    }
}
