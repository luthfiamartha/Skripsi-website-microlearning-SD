<div class="page-header">
    <div>
        <h1>{{ $modul ? 'Edit Modul' : 'Tambah Modul' }}</h1>
        <p>Satu modul berisi satu bagian materi secara singkat dan bertahap.</p>
    </div>
    <a class="btn btn-outline" href="{{ route($prefix . '.modul.index', ['materi' => $materiId]) }}">← Kembali</a>
</div>

<div class="card" style="max-width: 720px;">
    <form action="{{ $modul ? route($prefix . '.modul.update', $modul) : route($prefix . '.modul.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($modul)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="materi_id">Materi</label>
            <select class="form-select @error('materi_id') is-error @enderror" id="materi_id" name="materi_id" required>
                <option value="">Pilih materi</option>
                @foreach($materiList as $m)
                    <option value="{{ $m->id }}"
                        {{ (string) old('materi_id', $modul?->materi_id ?? $materiId) === (string) $m->id ? 'selected' : '' }}>
                        {{ $m->judul }}
                    </option>
                @endforeach
            </select>
            @error('materi_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="judul">Judul Modul</label>
            <input class="form-input @error('judul') is-error @enderror"
                   type="text" id="judul" name="judul"
                   value="{{ old('judul', $modul?->judul) }}"
                   placeholder="Contoh: Mengenal Makhluk Hidup" required>
            @error('judul')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="konten">Isi Penjelasan Sederhana</label>
            <textarea class="form-textarea @error('konten') is-error @enderror"
                      id="konten" name="konten" rows="6"
                      placeholder="Tuliskan penjelasan singkat dan mudah dipahami siswa kelas III SD"
                      required>{{ old('konten', $modul?->konten) }}</textarea>
            @error('konten')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <span class="form-hint">Gunakan paragraf pendek agar mudah dibaca.</span>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="urutan">Urutan Modul</label>
                <input class="form-input @error('urutan') is-error @enderror"
                       type="number" id="urutan" name="urutan" min="1"
                       value="{{ old('urutan', $modul?->urutan ?? 1) }}" required>
                @error('urutan')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="gambar">Gambar / Visualisasi (opsional)</label>
                <input class="form-input @error('gambar') is-error @enderror"
                       type="file" id="gambar" name="gambar" accept="image/*">
                @error('gambar')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Format: JPG, PNG, atau SVG. Maks 2MB.</span>
            </div>
        </div>

        @if($modul?->gambar)
            <div class="form-group">
                <span class="form-label">Gambar Saat Ini</span>
                <div class="modul-gambar" style="text-align: left;">
                    <img src="{{ asset('uploads/' . $modul->gambar) }}" alt="Gambar modul" style="max-height: 180px;">
                </div>
            </div>
        @endif

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $modul ? 'Simpan Perubahan' : 'Simpan Modul' }}</button>
            <a class="btn btn-outline" href="{{ route($prefix . '.modul.index', ['materi' => $materiId]) }}">Batal</a>
        </div>
    </form>
</div>
