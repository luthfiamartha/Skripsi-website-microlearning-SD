<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesUserAccounts;
use App\Models\HasilKuis;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGuruController extends Controller
{
    use ManagesUserAccounts;

    public function index()
    {
        $guruList = User::where('role', 'guru')
            ->orderBy('name')
            ->get()
            ->map(function (User $guru) {
                $pembelajaran = $guru->pembelajaranDibuat()->get(['nama']);

                return [
                    'guru' => $guru,
                    'jumlahPembelajaran' => $pembelajaran->count(),
                    'daftarPembelajaran' => $pembelajaran->pluck('nama'),
                ];
            });

        return view('admin.guru.index', compact('guruList'));
    }

    public function edit(User $guru)
    {
        abort_unless($guru->isGuru(), 404);

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        abort_unless($guru->isGuru(), 404);

        $data = $this->validasiAkun($request, $guru);
        $this->perbaruiAkun($guru, $data);

        return redirect()
            ->route('admin.guru.index')
            ->with('sukses', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Request $request, User $guru)
    {
        abort_unless($guru->isGuru(), 404);

        if ($guru->id === $request->user()->id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Pembelajaran buatan guru di-cascade oleh database. Agar progress dan
        // hasil kuis kelas tidak jatuh kembali ke data mandiri siswa (SET NULL),
        // baris kelas tersebut dihapus lebih dulu. Materi / modul / kuis global
        // tidak dimiliki per user sehingga ikut dipertahankan.
        DB::transaction(function () use ($guru) {
            $kelasIds = $guru->pembelajaranDibuat()->pluck('id');

            if ($kelasIds->isNotEmpty()) {
                HasilKuis::whereIn('pembelajaran_id', $kelasIds)->delete();
                Progress::whereIn('pembelajaran_id', $kelasIds)->delete();
            }

            $guru->delete();
        });

        return redirect()
            ->route('admin.guru.index')
            ->with('sukses', 'Data guru berhasil dihapus.');
    }
}
