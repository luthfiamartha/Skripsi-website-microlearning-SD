<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesUserAccounts;
use App\Http\Controllers\Concerns\SummarizesProgress;
use Illuminate\Http\Request;

class SiswaProfilController extends Controller
{
    use ManagesUserAccounts, SummarizesProgress;

    public function index(Request $request)
    {
        $siswa = $request->user();

        $ringkasan = $this->ringkasanBelajar($siswa->id);

        $pembelajaran = $siswa->pembelajaranDiikuti()->with('guru')->get();

        return view('siswa.profil', [
            'siswa' => $siswa,
            'ringkasan' => $ringkasan,
            'pembelajaran' => $pembelajaran,
        ]);
    }

    public function edit(Request $request)
    {
        return view('siswa.profil.edit', [
            'siswa' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $siswa = $request->user();

        $data = $this->validasiAkun($request, $siswa);
        $this->perbaruiAkun($siswa, $data);

        return redirect()
            ->route('siswa.profil')
            ->with('sukses', 'Profil berhasil diperbarui.');
    }
}
