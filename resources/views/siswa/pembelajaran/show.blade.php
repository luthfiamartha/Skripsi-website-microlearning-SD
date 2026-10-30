@extends('layouts.app')

@section('title', $pembelajaran->nama)

@section('content')
    <div class="page-header">
        <div>
            <div class="d-flex align-center gap-2 flex-wrap">
                <h1>👩‍🏫 {{ $pembelajaran->nama }}</h1>
                <span class="badge badge-primary">Kode: {{ $pembelajaran->kode }}</span>
            </div>
            <p>Guru: {{ $pembelajaran->guru->name ?? '-' }} • {{ $jumlahSiswa }} siswa bergabung</p>
        </div>
        <a class="btn btn-outline" href="{{ route('siswa.pembelajaran.index') }}">← Semua Pembelajaran</a>
    </div>

    @if($pembelajaran->deskripsi)
        <div class="card mb-2">
            <p class="mb-0">{{ $pembelajaran->deskripsi }}</p>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__label">Materi Selesai</div>
            <div class="stat-card__value">{{ $ringkasan['materiSelesai'] }} Materi</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Modul Selesai</div>
            <div class="stat-card__value">{{ $ringkasan['modulSelesai'] }} Modul</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Kuis Dikerjakan</div>
            <div class="stat-card__value">{{ $ringkasan['kuisDikerjakan'] }} Kuis</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Progress Pembelajaran</div>
            <div class="stat-card__value">{{ $ringkasan['persentase'] }}%</div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">📈 Progress Pembelajaran Ini</div>
            <span class="badge {{ $ringkasan['persentase'] === 100 ? 'badge-success' : 'badge-primary' }}">{{ $ringkasan['persentase'] }}%</span>
        </div>
        <div class="progress-label">
            <span>{{ $ringkasan['materiSelesai'] }} materi selesai • {{ $ringkasan['modulSelesai'] }} modul</span>
            <span>{{ $ringkasan['modulSelesai'] }} dari {{ $ringkasan['totalModul'] }} modul</span>
        </div>
        <div class="progress">
            <div class="progress__fill {{ $ringkasan['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $ringkasan['persentase'] }}%"></div>
        </div>
        <div class="d-flex gap-2 flex-wrap mt-2">
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.materi.index', ['pembelajaran' => $pembelajaran->id]) }}">📚 Semua Materi</a>
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.progress', ['pembelajaran' => $pembelajaran->id]) }}">📈 Progress Detail</a>
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.hasil.index') }}">🏆 Hasil Kuis</a>
        </div>
    </div>

    @if($ringkasan['materiSedang'])
        <h3 class="mb-2 section-title">📚 Lanjutkan Belajar</h3>
        @php $itemSedang = $ringkasan['daftarMateri']->firstWhere('materi.id', $ringkasan['materiSedang']->id); @endphp
        <div class="card materi-card">
            <div class="d-flex align-center justify-between flex-wrap gap-2">
                <div>
                    <div class="text-small text-secondary">IPA Kelas III SD</div>
                    <div class="materi-card__title">{{ $ringkasan['materiSedang']->judul }}</div>
                    <div class="text-small text-secondary">{{ \Illuminate\Support\Str::limit($ringkasan['materiSedang']->deskripsi ?? '', 110) }}</div>
                </div>
                <span class="badge badge-warning">💪 Semangat!</span>
            </div>
            <div class="progress-label mt-2">
                <span>Progress</span>
                <span>{{ $itemSedang['persentase'] ?? 0 }}%</span>
            </div>
            <div class="progress">
                <div class="progress__fill" style="width: {{ $itemSedang['persentase'] ?? 0 }}%"></div>
            </div>
            <div class="materi-card__footer mt-2">
                <span class="text-small text-secondary">Modul dipelajari: {{ $itemSedang['modul_selesai'] ?? 0 }} / {{ $itemSedang['modul_count'] ?? 0 }}</span>
                <a class="btn btn-primary btn-sm"
                   href="{{ route('siswa.materi.show', array_merge([$ringkasan['materiSedang']], ['pembelajaran' => $pembelajaran->id])) }}">
                    Lanjut Belajar ➡
                </a>
            </div>
        </div>
    @endif

    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">📚 Materi Pembelajaran</h3>
        <a class="text-small" href="{{ route('siswa.materi.index', ['pembelajaran' => $pembelajaran->id]) }}">Lihat semua →</a>
    </div>

    <div class="card-grid">
        @forelse($ringkasan['daftarMateri']->take(6) as $i => $item)
            @include('siswa.partials.materi-card', ['item' => $item, 'i' => $i, 'pembelajaran' => $pembelajaran])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">📭</div>
                <div class="empty-state__title">Belum ada materi tersedia</div>
                <div class="empty-state__text">Materi akan segera ditambahkan oleh guru.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">📝 Kuis Pembelajaran</h3>
        <a class="text-small" href="{{ route('siswa.kuis.index') }}">Semua kuis →</a>
    </div>

    <div class="card-grid">
        @forelse($daftarKuis as $i => $kuisItem)
            @include('siswa.partials.kuis-card', ['kuisItem' => $kuisItem, 'i' => $i, 'pembelajaran' => $pembelajaran])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">🧩</div>
                <div class="empty-state__title">Belum ada kuis dari guru.</div>
                <div class="empty-state__text">Kuis pembelajaran akan muncul setelah guru menambahkannya ke kelas ini.</div>
            </div>
        @endforelse
    </div>
@endsection
