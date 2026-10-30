<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPembelajaran;
use App\Http\Controllers\Concerns\SummarizesProgress;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;

class SiswaPembelajaranController extends Controller
{
    use ResolvesPembelajaran, SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $daftar = $siswa->pembelajaranDiikuti()
            ->with('guru')
            ->get()
            ->map(function (Pembelajaran $pembelajaran) use ($siswa) {
                $ringkasan = $this->ringkasanBelajar($siswa->id, $pembelajaran);

                return [
                    'pembelajaran' => $pembelajaran,
                    'persentase' => $ringkasan['persentase'],
                    'modulSelesai' => $ringkasan['modulSelesai'],
                    'totalModul' => $ringkasan['totalModul'],
                    'materiSelesai' => $ringkasan['materiSelesai'],
                    'jumlahMateri' => $ringkasan['daftarMateri']->count(),
                    'jumlahSiswa' => $pembelajaran->siswa()->count(),
                ];
            });

        return view('siswa.pembelajaran.index', compact('siswa', 'daftar'));
    }

    public function gabung(Request $request)
    {
        $siswa = $request->user();

        if (! $siswa || $siswa->role !== 'siswa') {
            abort(403, 'Hanya siswa yang dapat bergabung ke pembelajaran.');
        }

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20'],
        ], [
            'kode.required' => 'Kode pembelajaran wajib diisi.',
            'kode.max' => 'Kode pembelajaran terlalu panjang.',
        ], [
            'kode' => 'kode pembelajaran',
        ]);

        $kode = strtoupper(trim($data['kode']));

        $pembelajaran = Pembelajaran::where('kode', $kode)->first();

        if (! $pembelajaran) {
            return back()
                ->withErrors(['kode' => 'Kode pembelajaran tidak ditemukan.'])
                ->onlyInput('kode');
        }

        $sudahGabung = $pembelajaran->anggota()
            ->where('user_id', $siswa->id)
            ->exists();

        if ($sudahGabung) {
            return back()
                ->withErrors(['kode' => 'Kamu sudah bergabung ke pembelajaran ini.'])
                ->onlyInput('kode');
        }

        $pembelajaran->anggota()->create(['user_id' => $siswa->id]);

        return redirect()
            ->route('siswa.pembelajaran.show', $pembelajaran)
            ->with('sukses', 'Berhasil bergabung ke pembelajaran.');
    }

    public function show(Request $request, Pembelajaran $pembelajaran)
    {
        $siswa = $request->user();

        $sudahGabung = $pembelajaran->anggota()
            ->where('user_id', $siswa->id)
            ->exists();

        if (! $sudahGabung) {
            abort(403, 'Kamu belum bergabung ke pembelajaran ini.');
        }

        $ringkasan = $this->ringkasanBelajar($siswa->id, $pembelajaran);
        $daftarKuis = $this->daftarKuis($siswa->id, $ringkasan, $pembelajaran);

        return view('siswa.pembelajaran.show', [
            'siswa' => $siswa,
            'pembelajaran' => $pembelajaran,
            'ringkasan' => $ringkasan,
            'daftarKuis' => $daftarKuis,
            'jumlahSiswa' => $pembelajaran->siswa()->count(),
        ]);
    }
}
