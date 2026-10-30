<div class="page-header">
    <div>
        <h1>Kelola Kuis</h1>
        <p>Kelola kuis evaluasi dan soal untuk setiap materi IPA.</p>
    </div>
    <a class="btn btn-primary" href="{{ route($prefix . '.kuis.create') }}">+ Tambah Kuis</a>
</div>

@if($kuis->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon">◻</div>
        <div class="empty-state__title">Belum ada kuis</div>
        <div class="empty-state__text">Klik "Tambah Kuis" untuk membuat kuis beserta soalnya.</div>
    </div>
@else
    <div class="table-wrap">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>Judul Kuis</th>
                <th>Materi</th>
                <th>Jumlah Soal</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($kuis as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->judul }}</td>
                    <td>{{ $row->materi->judul ?? '-' }}</td>
                    <td>{{ $row->soal_count }} soal</td>
                    <td class="aksi">
                        <a class="btn btn-secondary btn-sm" href="{{ route($prefix . '.kuis.edit', $row) }}">Edit</a>
                        <form action="{{ route($prefix . '.kuis.destroy', $row) }}" method="POST"
                              onsubmit="return confirm('Hapus kuis beserta soal di dalamnya?');">
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
