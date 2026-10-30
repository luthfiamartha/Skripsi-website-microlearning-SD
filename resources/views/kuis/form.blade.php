<div class="page-header">
    <div>
        <h1>{{ $kuis ? 'Edit Kuis' : 'Tambah Kuis' }}</h1>
        <p>Isi judul kuis, materi terkait, beserta daftar soal dan kunci jawaban.</p>
    </div>
    <a class="btn btn-outline" href="{{ route($prefix . '.kuis.index') }}">← Kembali</a>
</div>

<form action="{{ $kuis ? route($prefix . '.kuis.update', $kuis) : route($prefix . '.kuis.store') }}" method="POST">
    @csrf
    @if($kuis)
        @method('PUT')
    @endif

    <div class="card" style="max-width: 860px;">
        <div class="form-group">
            <label class="form-label" for="judul">Judul Kuis</label>
            <input class="form-input @error('judul') is-error @enderror"
                   type="text" id="judul" name="judul"
                   value="{{ old('judul', $kuis?->judul) }}"
                   placeholder="Contoh: Kuis Makhluk Hidup" required>
            @error('judul')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-0">
            <label class="form-label" for="materi_id">Materi</label>
            <select class="form-select @error('materi_id') is-error @enderror" id="materi_id" name="materi_id" required>
                <option value="">Pilih materi</option>
                @foreach($materiList as $m)
                    <option value="{{ $m->id }}"
                        {{ (string) old('materi_id', $kuis?->materi_id) === (string) $m->id ? 'selected' : '' }}>
                        {{ $m->judul }}
                    </option>
                @endforeach
            </select>
            @error('materi_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="page-header">
        <div>
            <h3>Daftar Soal</h3>
            <p>Setiap soal memiliki 4 pilihan jawaban dan satu kunci jawaban.</p>
        </div>
        <button class="btn btn-secondary" type="button" id="tambah-soal">+ Tambah Soal</button>
    </div>

    @error('soal')
        <div class="alert alert-danger"><span>{{ $message }}</span></div>
    @enderror

    <div id="daftar-soal" style="max-width: 860px;">
        @foreach($daftarSoal as $i => $soal)
            <div class="soal-block">
                <div class="soal-block__header">
                    <span>Soal {{ $i + 1 }}</span>
                </div>
                <input type="hidden" name="soal[{{ $i }}][id]" value="{{ $soal->id }}">

                <div class="form-group">
                    <label class="form-label">Pertanyaan</label>
                    <textarea class="form-textarea" name="soal[{{ $i }}][pertanyaan]"
                              required>{{ old("soal.$i.pertanyaan", $soal->pertanyaan) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pilihan A</label>
                        <input class="form-input" type="text" name="soal[{{ $i }}][pilihan_a]"
                               value="{{ old("soal.$i.pilihan_a", $soal->pilihan_a) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilihan B</label>
                        <input class="form-input" type="text" name="soal[{{ $i }}][pilihan_b]"
                               value="{{ old("soal.$i.pilihan_b", $soal->pilihan_b) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pilihan C</label>
                        <input class="form-input" type="text" name="soal[{{ $i }}][pilihan_c]"
                               value="{{ old("soal.$i.pilihan_c", $soal->pilihan_c) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilihan D</label>
                        <input class="form-input" type="text" name="soal[{{ $i }}][pilihan_d]"
                               value="{{ old("soal.$i.pilihan_d", $soal->pilihan_d) }}" required>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Jawaban Benar</label>
                    <select class="form-select" name="soal[{{ $i }}][kunci_jawaban]" required>
                        <option value="">Pilih jawaban benar</option>
                        @foreach(['a', 'b', 'c', 'd'] as $key)
                            <option value="{{ $key }}"
                                {{ old("soal.$i.kunci_jawaban", $soal->kunci_jawaban) === $key ? 'selected' : '' }}>
                                {{ strtoupper($key) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        @if($daftarSoal->isEmpty())
            <div class="soal-block" id="blok-soal-pertama">
                <div class="soal-block__header">
                    <span>Soal 1</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan</label>
                    <textarea class="form-textarea" name="soal[0][pertanyaan]"
                              required>{{ old('soal.0.pertanyaan') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pilihan A</label>
                        <input class="form-input" type="text" name="soal[0][pilihan_a]"
                               value="{{ old('soal.0.pilihan_a') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilihan B</label>
                        <input class="form-input" type="text" name="soal[0][pilihan_b]"
                               value="{{ old('soal.0.pilihan_b') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pilihan C</label>
                        <input class="form-input" type="text" name="soal[0][pilihan_c]"
                               value="{{ old('soal.0.pilihan_c') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilihan D</label>
                        <input class="form-input" type="text" name="soal[0][pilihan_d]"
                               value="{{ old('soal.0.pilihan_d') }}" required>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Jawaban Benar</label>
                    <select class="form-select" name="soal[0][kunci_jawaban]" required>
                        <option value="">Pilih jawaban benar</option>
                        @foreach(['a', 'b', 'c', 'd'] as $key)
                            <option value="{{ $key }}" {{ old('soal.0.kunci_jawaban') === $key ? 'selected' : '' }}>
                                {{ strtoupper($key) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex gap-2 mt-2">
        <button class="btn btn-primary btn-lg" type="submit">{{ $kuis ? 'Simpan Perubahan' : 'Simpan Kuis' }}</button>
        <a class="btn btn-outline btn-lg" href="{{ route($prefix . '.kuis.index') }}">Batal</a>
    </div>
</form>
