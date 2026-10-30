<div class="page-header">
    <div>
        <a class="text-small" href="{{ route($prefix . '.materi.index') }}">← Kembali ke daftar materi</a>
        <h1>{{ $materi->judul }}</h1>
        <p>{{ $materi->deskripsi }}</p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-secondary" href="{{ route($prefix . '.materi.edit', $materi) }}">Edit Materi</a>
    </div>
</div>

<div class="card">
    <div class="card__header">
        <div class="card__title">Modul Micro-Learning</div>
        <a class="btn btn-primary btn-sm" href="{{ route($prefix . '.modul.create', ['materi' => $materi->id]) }}">+ Tambah Modul</a>
    </div>

    @if($materi->modul->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada modul</div>
            <div class="empty-state__text">Tambahkan modul singkat untuk materi ini.</div>
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
                @foreach($materi->modul as $modul)
                    <tr>
                        <td>{{ $modul->urutan }}</td>
                        <td>{{ $modul->judul }}</td>
                        <td>
                            @if($modul->gambar)
                                <span class="badge badge-info">Ada Gambar</span>
                            @else
                                <span class="badge badge-muted">Tidak Ada</span>
                            @endif
                        </td>
                        <td class="aksi">
                            <a class="btn btn-secondary btn-sm" href="{{ route($prefix . '.modul.edit', $modul) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="card">
    <div class="card__header">
        <div class="card__title">Kuis Evaluasi</div>
        @if($materi->kuis)
            <a class="btn btn-secondary btn-sm" href="{{ route($prefix . '.kuis.edit', $materi->kuis) }}">Edit Kuis</a>
        @else
            <a class="btn btn-primary btn-sm" href="{{ route($prefix . '.kuis.create') }}">+ Tambah Kuis</a>
        @endif
    </div>

    @if($materi->kuis)
        <p class="mb-0">
            <strong>{{ $materi->kuis->judul }}</strong><br>
            <span class="text-small text-secondary">Jumlah soal: {{ $materi->kuis->soal->count() }}</span>
        </p>
    @else
        <div class="empty-state mb-0">
            <div class="empty-state__title">Belum ada kuis</div>
            <div class="empty-state__text">Kuis dibuat setelah materi dan modul tersedia.</div>
        </div>
    @endif
</div>
