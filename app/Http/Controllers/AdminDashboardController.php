<?php

namespace App\Http\Controllers;

use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Materi;
use App\Models\Modul;
use App\Models\Pembelajaran;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $jumlahSiswa = User::where('role', 'siswa')->count();
        $jumlahMateri = Materi::count();
        $jumlahModul = Modul::count();
        $jumlahKuis = Kuis::count();
        $jumlahPembelajaran = Pembelajaran::count();

        $totalModul = Modul::count();
        $modulSelesai = (int) Progress::whereNull('pembelajaran_id')->sum('modul_selesai');
        $rataProgress = $totalModul > 0 && $jumlahSiswa > 0
            ? round($modulSelesai / ($totalModul * $jumlahSiswa) * 100)
            : 0;

        $jumlahHasilKuis = HasilKuis::count();

        return view('admin.dashboard', compact(
            'jumlahSiswa', 'jumlahMateri', 'jumlahModul', 'jumlahKuis',
            'rataProgress', 'jumlahHasilKuis', 'jumlahPembelajaran'
        ));
    }
}
