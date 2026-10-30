<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;

class AdminMateriController extends Controller
{
    public function index()
    {
        $materi = Materi::withCount(['modul', 'kuis'])->orderBy('id')->get();

        return view('admin.materi.index', compact('materi'));
    }

    public function create()
    {
        return view('admin.materi.form', ['materi' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Materi::create($data);

        return redirect()->route('admin.materi.index')
            ->with('sukses', 'Materi berhasil ditambahkan.');
    }

    public function show(Materi $materi)
    {
        $materi->load(['modul', 'kuis.soal']);

        return view('admin.materi.show', compact('materi'));
    }

    public function edit(Materi $materi)
    {
        return view('admin.materi.form', compact('materi'));
    }

    public function update(Request $request, Materi $materi)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $materi->update($data);

        return redirect()->route('admin.materi.index')
            ->with('sukses', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();

        return redirect()->route('admin.materi.index')
            ->with('sukses', 'Materi berhasil dihapus.');
    }
}
