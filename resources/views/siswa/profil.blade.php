@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')
    <div class="page-header">
        <div>
            <div class="d-flex align-center gap-2">
                <span class="avatar avatar--lg">{{ mb_strtoupper(mb_substr($siswa->name, 0, 1)) }}</span>
                <div>
                    <h1>{{ $siswa->name }}</h1>
                    <p>👤 {{ $siswa->username }} • 📧 {{ $siswa->email }} • 🎓 Siswa</p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('siswa.profil.edit') }}">✏️ Edit Profil</a>
            <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__label">Materi Dipelajari</div>
            <div class="stat-card__value">{{ $ringkasan['materiDipelajari'] }} Materi</div>
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
            <div class="stat-card__label">Progress Belajar Mandiri</div>
            <div class="stat-card__value">{{ $ringkasan['persentase'] }}%</div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">📊 Ringkasan Belajar Mandiri</div>
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

    <div class="card__header">
        <div class="card__title">👩‍🏫 Pembelajaran Diikuti</div>
        <a class="btn btn-outline btn-sm" href="{{ route('siswa.pembelajaran.index') }}">Kelola</a>
    </div>

    @if($pembelajaran->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum mengikuti pembelajaran</div>
            <div class="empty-state__text">Gabung dengan kode pembelajaran dari gurumu untuk mengikuti kelas.</div>
            <a class="btn btn-fun mt-2" href="{{ route('siswa.pembelajaran.index') }}#gabung">🎟️ Gabung Pembelajaran</a>
        </div>
    @else
        <div class="table-wrap mb-2">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Pembelajaran</th>
                    <th>Guru</th>
                    <th>Kode</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pembelajaran as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->guru->name ?? '-' }}</td>
                        <td><span class="badge badge-primary">{{ $p->kode }}</span></td>
                        <td class="aksi">
                            <a class="btn btn-outline btn-sm" href="{{ route('siswa.pembelajaran.show', $p) }}">Buka</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="d-flex gap-2 flex-wrap mt-2">
        <a class="btn btn-outline" href="{{ route('siswa.hasil.index') }}">🏆 Hasil Kuis</a>
        <a class="btn btn-outline" href="{{ route('siswa.progress') }}">📈 Progress Detail</a>
        <a class="btn btn-outline" href="{{ route('logout.get') }}">🚪 Logout</a>
    </div>
@endsection
