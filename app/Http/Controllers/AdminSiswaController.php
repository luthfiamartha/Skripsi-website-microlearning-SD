<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesUserAccounts;
use App\Models\Materi;
use App\Models\PembelajaranAnggota;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSiswaController extends Controller
{
    use ManagesUserAccounts;

    public function index(Request $request)
    {
        $siswaList = User::where('role', 'siswa')->orderBy('name')->get();
        $totalModul = Materi::withCount('modul')->get()->sum('modul_count');

        // Angka pada halaman ini adalah progress belajar mandiri
        $semuaProgress = Progress::whereNull('pembelajaran_id')->get();

        $anggotaMap = PembelajaranAnggota::query()
            ->select('user_id', DB::raw('COUNT(*) as jumlah'), DB::raw('MIN(pembelajaran_id) as pertama'))
            ->whereIn('user_id', $siswaList->pluck('id'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $daftar = $siswaList->map(function ($siswa) use ($semuaProgress, $totalModul, $anggotaMap) {
            $progressSiswa = $semuaProgress->where('user_id', $siswa->id);
            $modulSelesai = $progressSiswa->sum('modul_selesai');
            $anggota = $anggotaMap->get($siswa->id);

            return [
                'siswa' => $siswa,
                'persentase' => $totalModul > 0 ? round($modulSelesai / $totalModul * 100) : 0,
                'jumlahPembelajaran' => (int) ($anggota->jumlah ?? 0),
                'pembelajaranPertama' => $anggota->pertama ?? null,
            ];
        });

        return view('admin.siswa.index', compact('daftar'));
    }

    public function edit(User $siswa)
    {
        abort_unless($siswa->isSiswa(), 404);

        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, User $siswa)
    {
        abort_unless($siswa->isSiswa(), 404);

        $data = $this->validasiAkun($request, $siswa);
        $this->perbaruiAkun($siswa, $data);

        return redirect()
            ->route('admin.siswa.index')
            ->with('sukses', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Request $request, User $siswa)
    {
        abort_unless($siswa->isSiswa(), 404);

        if ($siswa->id === $request->user()->id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Relasi user (progress, hasil_kuis -> jawaban_siswa, pembelajaran_anggota)
        // memakai ON DELETE CASCADE, sehingga tidak ada data yatim / foreign key error.
        DB::transaction(function () use ($siswa) {
            $siswa->delete();
        });

        return redirect()
            ->route('admin.siswa.index')
            ->with('sukses', 'Data siswa berhasil dihapus.');
    }
}
