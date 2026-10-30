<div class="page-header">
    <div>
        <h1>Kelola Materi</h1>
        <p>Tambah, ubah, lihat, dan hapus materi pembelajaran IPA kelas III SD.</p>
    </div>
    <a class="btn btn-primary" href="{{ route($prefix . '.materi.create') }}">+ Tambah Materi</a>
</div>

@if($materi->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon">◻</div>
        <div class="empty-state__title">Belum ada materi tersedia</div>
        <div class="empty-state__text">Klik "Tambah Materi" untuk membuat materi pertama.</div>
    </div>
@else
    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>Judul Materi</th>
                <th>Jumlah Modul</th>
                <th>Kuis</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($materi as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->judul }}</td>
                    <td>{{ $row->modul_count }} modul</td>
                    <td>
                        @if($row->kuis_count > 0)
                            <span class="badge badge-success">Ada Kuis</span>
                        @else
                            <span class="badge badge-muted">Belum Ada</span>
                        @endif
                    </td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-sm" href="{{ route($prefix . '.materi.show', $row) }}">Lihat</a>
                        <a class="btn btn-secondary btn-sm" href="{{ route($prefix . '.materi.edit', $row) }}">Edit</a>
                        <form action="{{ route($prefix . '.materi.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Hapus materi beserta modul dan kuis di dalamnya?');">
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
