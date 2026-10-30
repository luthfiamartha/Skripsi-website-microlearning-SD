<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruKuisController extends Controller
{
    public function index()
    {
        $kuis = Kuis::withCount('soal')->with('materi')->orderBy('id')->get();

        return view('guru.kuis.index', compact('kuis'));
    }

    public function create()
    {
        $materiList = Materi::orderBy('id')->get();

        if ($materiList->isEmpty()) {
            return redirect()->route('guru.materi.index')
                ->with('error', 'Buat materi terlebih dahulu sebelum menambah kuis.');
        }

        return view('guru.kuis.form', ['kuis' => null, 'materiList' => $materiList, 'daftarSoal' => collect()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasiKuis($request);

        try {
            DB::beginTransaction();

            $kuis = Kuis::create([
                'materi_id' => $data['materi_id'],
                'judul' => $data['judul'],
            ]);

            foreach ($data['soal'] as $i => $soal) {
                $kuis->soal()->create([
                    'pertanyaan' => $soal['pertanyaan'],
                    'pilihan_a' => $soal['pilihan_a'],
                    'pilihan_b' => $soal['pilihan_b'],
                    'pilihan_c' => $soal['pilihan_c'],
                    'pilihan_d' => $soal['pilihan_d'],
                    'kunci_jawaban' => $soal['kunci_jawaban'],
                    'urutan' => $i + 1,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan kuis. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('guru.kuis.index')
            ->with('sukses', 'Kuis berhasil ditambahkan.');
    }

    public function edit(Kuis $kuis)
    {
        $kuis->load('soal');
        $materiList = Materi::orderBy('id')->get();

        return view('guru.kuis.form', [
            'kuis' => $kuis,
            'materiList' => $materiList,
            'daftarSoal' => $kuis->soal,
        ]);
    }

    public function update(Request $request, Kuis $kuis)
    {
        $data = $this->validasiKuis($request);

        try {
            DB::beginTransaction();

            $kuis->update([
                'materi_id' => $data['materi_id'],
                'judul' => $data['judul'],
            ]);

            $idTerpakai = [];

            foreach ($data['soal'] as $i => $soal) {
                $payload = [
                    'pertanyaan' => $soal['pertanyaan'],
                    'pilihan_a' => $soal['pilihan_a'],
                    'pilihan_b' => $soal['pilihan_b'],
                    'pilihan_c' => $soal['pilihan_c'],
                    'pilihan_d' => $soal['pilihan_d'],
                    'kunci_jawaban' => $soal['kunci_jawaban'],
                    'urutan' => $i + 1,
                ];

                if (! empty($soal['id'])) {
                    $existing = $kuis->soal()->find($soal['id']);
                    if ($existing) {
                        $existing->update($payload);
                        $idTerpakai[] = $existing->id;
                        continue;
                    }
                }

                $baru = $kuis->soal()->create($payload);
                $idTerpakai[] = $baru->id;
            }

            $kuis->soal()->whereNotIn('id', $idTerpakai)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memperbarui kuis. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('guru.kuis.index')
            ->with('sukses', 'Kuis berhasil diperbarui.');
    }

    public function destroy(Kuis $kuis)
    {
        $kuis->delete();

        return redirect()->route('guru.kuis.index')
            ->with('sukses', 'Kuis berhasil dihapus.');
    }

    protected function validasiKuis(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'materi_id' => ['required', 'integer', 'exists:materi,id'],
            'soal' => ['required', 'array', 'min:1'],
            'soal.*.pertanyaan' => ['required', 'string'],
            'soal.*.pilihan_a' => ['required', 'string'],
            'soal.*.pilihan_b' => ['required', 'string'],
            'soal.*.pilihan_c' => ['required', 'string'],
            'soal.*.pilihan_d' => ['required', 'string'],
            'soal.*.kunci_jawaban' => ['required', 'in:a,b,c,d'],
            'soal.*.id' => ['nullable', 'integer'],
        ]);
    }
}
