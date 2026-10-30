<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Pembelajaran;
use App\Models\Progress;
use Illuminate\Http\Request;

class GuruPembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();

        $totalModul = Modul::count();

        $daftar = $guru->pembelajaranDibuat()
            ->withCount('siswa')
            ->orderByDesc('id')
            ->get()
            ->map(function (Pembelajaran $pembelajaran) use ($totalModul) {
                $modulSelesai = (int) Progress::where('pembelajaran_id', $pembelajaran->id)
                    ->sum('modul_selesai');

                $jumlahSiswa = (int) $pembelajaran->siswa_count;

                return [
                    'pembelajaran' => $pembelajaran,
                    'jumlahSiswa' => $jumlahSiswa,
                    'modulSelesai' => $modulSelesai,
                    'persentase' => $totalModul > 0 && $jumlahSiswa > 0
                        ? round($modulSelesai / ($totalModul * $jumlahSiswa) * 100)
                        : 0,
                ];
            });

        return view('guru.pembelajaran.index', compact('guru', 'daftar'));
    }

    public function store(Request $request)
    {
        $guru = $request->user();

        if (! $guru || $guru->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat membuat pembelajaran.');
        }

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama.required' => 'Nama pembelajaran wajib diisi.',
            'nama.max' => 'Nama pembelajaran maksimal 100 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 255 karakter.',
        ], [
            'nama' => 'nama pembelajaran',
            'deskripsi' => 'deskripsi',
        ]);

        $pembelajaran = Pembelajaran::create([
            'user_id' => $guru->id,
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'kode' => $this->kodeBaru(),
        ]);

        return redirect()
            ->route('guru.pembelajaran.index')
            ->with('sukses', 'Pembelajaran "' . $pembelajaran->nama . '" dibuat. Bagikan kode ' . $pembelajaran->kode . ' kepada siswa.');
    }

    private function kodeBaru(): string
    {
        $huruf = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $kode = '';
            for ($i = 0; $i < 6; $i++) {
                $kode .= $huruf[random_int(0, strlen($huruf) - 1)];
            }
        } while (Pembelajaran::where('kode', $kode)->exists());

        return $kode;
    }
}
