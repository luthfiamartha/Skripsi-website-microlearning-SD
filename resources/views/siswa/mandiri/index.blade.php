@extends('layouts.app')

@section('title', 'Belajar Mandiri')

@section('content')
    <div class="page-header">
        <div>
            <h1>🌱 Belajar Mandiri</h1>
            <p>Belajar sesuai kecepatanmu tanpa harus bergabung dengan pembelajaran guru. Materi, modul, dan kuis di sini tidak bercampur dengan pembelajaran kelas.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__label">Total Materi</div>
            <div class="stat-card__value">{{ $jumlahMateri }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Total Modul</div>
            <div class="stat-card__value">{{ $jumlahModul }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Total Kuis</div>
            <div class="stat-card__value">{{ $jumlahKuis }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Progress Belajar</div>
            <div class="stat-card__value">{{ $ringkasan['persentase'] }}%</div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">📈 Progress Belajar Mandiri</div>
            <span class="badge {{ $ringkasan['persentase'] === 100 ? 'badge-success' : 'badge-primary' }}">{{ $ringkasan['persentase'] }}%</span>
        </div>
        <div class="progress-label">
            <span>{{ $ringkasan['materiSelesai'] }} materi selesai • {{ $ringkasan['modulSelesai'] }} modul</span>
            <span>{{ $ringkasan['modulSelesai'] }} dari {{ $ringkasan['totalModul'] }} modul</span>
        </div>
        <div class="progress">
            <div class="progress__fill {{ $ringkasan['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $ringkasan['persentase'] }}%"></div>
        </div>
    </div>

    @if($ringkasan['materiSedang'])
        <div class="card mode-card">
            <div class="d-flex align-center justify-between flex-wrap gap-2">
                <div>
                    <div class="text-small text-secondary">📚 Lanjutkan belajar</div>
                    <div class="mode-card__title">{{ $ringkasan['materiSedang']->judul }}</div>
                    <div class="text-small text-secondary">{{ \Illuminate\Support\Str::limit($ringkasan['materiSedang']->deskripsi ?? '', 110) }}</div>
                </div>
                <a class="btn btn-fun" href="{{ route('siswa.materi.show', $ringkasan['materiSedang']) }}">Lanjut Belajar ➡</a>
            </div>
        </div>
    @endif

    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">📚 Materi Belajar Mandiri</h3>
        <a class="text-small" href="{{ route('siswa.materi.index') }}">Lihat semua materi →</a>
    </div>
    <div class="card-grid">
        @forelse($ringkasan['daftarMateri'] as $i => $item)
            @include('siswa.partials.materi-card', ['item' => $item, 'i' => $i, 'pembelajaran' => null])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">📭</div>
                <div class="empty-state__title">Belum ada materi tersedia</div>
                <div class="empty-state__text">Materi IPA akan segera ditambahkan oleh guru.</div>
            </div>
        @endforelse
    </div>

    <h3 class="mb-2 section-title">📝 Kuis Belajar Mandiri</h3>
    <div class="card-grid">
        @forelse($daftarKuis as $i => $kuisItem)
            @include('siswa.partials.kuis-card', ['kuisItem' => $kuisItem, 'i' => $i, 'pembelajaran' => null])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">🧩</div>
                <div class="empty-state__title">Belum ada kuis</div>
                <div class="empty-state__text">Kuis akan muncul setelah guru menambahkannya.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-between gap-2 flex-wrap mt-2 mb-2">
        <a class="btn btn-outline" href="{{ route('siswa.hasil.index') }}">🏆 Hasil Kuis</a>
        <a class="btn btn-outline" href="{{ route('siswa.progress') }}">📈 Progress Detail</a>
    </div>
@endsection
