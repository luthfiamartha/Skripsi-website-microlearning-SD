<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Modul;
use Illuminate\Http\Request;

class GuruModulController extends Controller
{
    public function index(Request $request)
    {
        $materiList = Materi::orderBy('id')->get();
        $materiId = $request->query('materi');

        $modul = collect();

        if ($materiId) {
            $modul = Modul::where('materi_id', $materiId)->orderBy('urutan')->get();
        } elseif ($materiList->isNotEmpty()) {
            $materiId = $materiList->first()->id;
            $modul = Modul::where('materi_id', $materiId)->orderBy('urutan')->get();
        }

        return view('guru.modul.index', compact('materiList', 'materiId', 'modul'));
    }

    public function create(Request $request)
    {
        $materiList = Materi::orderBy('id')->get();

        if ($materiList->isEmpty()) {
            return redirect()->route('guru.materi.index')
                ->with('error', 'Buat materi terlebih dahulu sebelum menambah modul.');
        }

        $materiId = $request->query('materi', $materiList->first()->id);

        return view('guru.modul.form', ['modul' => null, 'materiList' => $materiList, 'materiId' => $materiId]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'materi_id' => ['required', 'integer', 'exists:materi,id'],
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'urutan' => ['required', 'integer', 'min:1'],
            'gambar' => ['nullable', 'image|mimes:jpg,jpeg,png,svg|max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->simpanGambar($request);
        } else {
            unset($data['gambar']);
        }

        Modul::create($data);

        return redirect()->route('guru.modul.index', ['materi' => $request->materi_id])
            ->with('sukses', 'Modul berhasil ditambahkan.');
    }

    public function edit(Modul $modul)
    {
        $materiList = Materi::orderBy('id')->get();

        return view('guru.modul.form', [
            'modul' => $modul,
            'materiList' => $materiList,
            'materiId' => $modul->materi_id,
        ]);
    }

    public function update(Request $request, Modul $modul)
    {
        $data = $request->validate([
            'materi_id' => ['required', 'integer', 'exists:materi,id'],
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'urutan' => ['required', 'integer', 'min:1'],
            'gambar' => ['nullable', 'image|mimes:jpg,jpeg,png,svg|max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->simpanGambar($request);
        } else {
            unset($data['gambar']);
        }

        $modul->update($data);

        return redirect()->route('guru.modul.index', ['materi' => $request->materi_id])
            ->with('sukses', 'Modul berhasil diperbarui.');
    }

    public function destroy(Modul $modul)
    {
        $materiId = $modul->materi_id;
        $modul->delete();

        return redirect()->route('guru.modul.index', ['materi' => $materiId])
            ->with('sukses', 'Modul berhasil dihapus.');
    }

    protected function simpanGambar(Request $request): string
    {
        $file = $request->file('gambar');
        $nama = 'modul_'.time().'_'.uniqid().'.'.$file->extension();
        $file->move(public_path('uploads'), $nama);

        return $nama;
    }
}
