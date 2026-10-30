<?php

namespace App\Http\Controllers;

use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\PembelajaranAnggota;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;

class GuruDashboardController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();

        // Hanya pembelajaran milik guru ini
        $daftarPembelajaran = $guru->pembelajaranDibuat()->withCount('siswa')->get();
        $idPembelajaran = $daftarPembelajaran->pluck('id');

        $jumlahPembelajaran = $daftarPembelajaran->count();

        $jumlahSiswa = User::where('role', 'siswa')
            ->whereIn('id', PembelajaranAnggota::whereIn('pembelajaran_id', $idPembelajaran)->pluck('user_id'))
            ->count();

        $jumlahMateri = Materi::count();
        $jumlahModul = Modul::count();
        $jumlahKuis = Kuis::count();

        $totalModul = Modul::count();
        $modulSelesai = (int) Progress::whereIn('pembelajaran_id', $idPembelajaran)->sum('modul_selesai');
        $rataProgress = $totalModul > 0 && $jumlahSiswa > 0
            ? round($modulSelesai / ($totalModul * $jumlahSiswa) * 100)
            : 0;

        $hasilTerbaru = HasilKuis::with(['siswa', 'kuis.materi'])
            ->whereIn('pembelajaran_id', $idPembelajaran)
            ->latest()
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'jumlahSiswa', 'jumlahMateri', 'jumlahModul', 'jumlahKuis',
            'rataProgress', 'hasilTerbaru', 'jumlahPembelajaran'
        ));
    }
}
