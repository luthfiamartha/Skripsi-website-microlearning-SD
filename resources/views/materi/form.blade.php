<div class="page-header">
    <div>
        <h1>{{ $materi ? 'Edit Materi' : 'Tambah Materi' }}</h1>
        <p>{{ $materi ? 'Perbarui data materi pembelajaran.' : 'Buat materi pembelajaran IPA baru.' }}</p>
    </div>
    <a class="btn btn-outline" href="{{ route($prefix . '.materi.index') }}">← Kembali</a>
</div>

<div class="card" style="max-width: 720px;">
    <form action="{{ $materi ? route($prefix . '.materi.update', $materi) : route($prefix . '.materi.store') }}" method="POST">
        @csrf
        @if($materi)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="judul">Judul Materi</label>
            <input class="form-input @error('judul') is-error @enderror"
                   type="text" id="judul" name="judul"
                   value="{{ old('judul', $materi?->judul) }}"
                   placeholder="Contoh: Makhluk Hidup dan Lingkungan" required>
            @error('judul')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi Singkat</label>
            <textarea class="form-textarea @error('deskripsi') is-error @enderror"
                      id="deskripsi" name="deskripsi"
                      placeholder="Ringkasan singkat materi">{{ old('deskripsi', $materi?->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ $materi ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
            <a class="btn btn-outline" href="{{ route($prefix . '.materi.index') }}">Batal</a>
        </div>
    </form>
</div>
