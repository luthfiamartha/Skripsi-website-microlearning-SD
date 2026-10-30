<div class="page-header">
    <div>
        <h1>Kelola Modul</h1>
        <p>Kelola modul micro-learning untuk setiap materi IPA.</p>
    </div>
    <a class="btn btn-primary" href="{{ route($prefix . '.modul.create', $materiId ? ['materi' => $materiId] : []) }}">+ Tambah Modul</a>
</div>

@if($materiList->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon">◻</div>
        <div class="empty-state__title">Belum ada materi tersedia</div>
        <div class="empty-state__text">Buat materi terlebih dahulu sebelum menambah modul.</div>
    </div>
@else
    <div class="card">
        <form action="{{ route($prefix . '.modul.index') }}" method="GET" class="d-flex gap-2 align-center flex-wrap">
            <div class="form-group mb-0" style="min-width: 260px;">
                <label class="form-label" for="materi">Pilih Materi</label>
                <select class="form-select" id="materi" name="materi" onchange="this.form.submit()">
                    @foreach($materiList as $m)
                        <option value="{{ $m->id }}" {{ (string) $materiId === (string) $m->id ? 'selected' : '' }}>
                            {{ $m->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <noscript><button class="btn btn-outline" type="submit">Tampilkan</button></noscript>
        </form>
    </div>

    @if($modul->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada modul pada materi ini</div>
            <div class="empty-state__text">Klik "Tambah Modul" untuk menambahkan modul micro-learning.</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Urutan</th>
                    <th>Judul Modul</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($modul as $row)
                    <tr>
                        <td>{{ $row->urutan }}</td>
                        <td>{{ $row->judul }}</td>
                        <td>
                            @if($row->gambar)
                                <span class="badge badge-info">Ada Gambar</span>
                            @else
                                <span class="badge badge-muted">Tidak Ada</span>
                            @endif
                        </td>
                        <td class="aksi">
                            <a class="btn btn-secondary btn-sm" href="{{ route($prefix . '.modul.edit', $row) }}">Edit</a>
                            <form action="{{ route($prefix . '.modul.destroy', $row) }}" method="POST"
                                  onsubmit="return confirm('Hapus modul ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif
