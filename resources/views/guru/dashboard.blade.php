@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Guru</h1>
            <p>Kelola pembelajaran IPA dan pantau perkembangan belajar siswa.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-fun" href="{{ route('guru.pembelajaran.index') }}">+ Buat Pembelajaran</a>
            <a class="btn btn-primary" href="{{ route('guru.materi.create') }}">+ Tambah Materi</a>
            <a class="btn btn-outline" href="{{ route('guru.kuis.create') }}">+ Tambah Kuis</a>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__label">Pembelajaran</div>
            <div class="stat-card__value">{{ $jumlahPembelajaran }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Siswa Bergabung</div>
            <div class="stat-card__value">{{ $jumlahSiswa }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Jumlah Materi</div>
            <div class="stat-card__value">{{ $jumlahMateri }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Rata-rata Progress</div>
            <div class="stat-card__value">{{ $rataProgress }}%</div>
        </div>
    </div>

    @if($jumlahPembelajaran === 0)
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum ada pembelajaran</div>
            <div class="empty-state__text">Buat pembelajaran lalu bagikan kode-nya ke siswa. Progress siswa baru bisa dipantau setelah mereka bergabung.</div>
            <a class="btn btn-fun mt-2" href="{{ route('guru.pembelajaran.index') }}">➕ Buat Pembelajaran</a>
        </div>
    @endif

    <div class="card">
        <div class="card__header">
            <div class="card__title">Ringkasan Progress Siswa</div>
            <a class="btn btn-outline btn-sm" href="{{ route('guru.progress') }}">Lihat Progress</a>
        </div>
        <div class="progress-label">
            <span>Rata-rata progress siswa di pembelajaranmu</span>
            <span>{{ $rataProgress }}%</span>
        </div>
        <div class="progress">
            <div class="progress__fill {{ $rataProgress === 100 ? 'progress__fill--success' : '' }}"
                 style="width: {{ $rataProgress }}%"></div>
        </div>
        <p class="text-small text-secondary mt-2 mb-0">
            Total {{ $jumlahKuis }} kuis tersedia untuk evaluasi pembelajaran.
        </p>
    </div>

    <div class="card__header">
        <div class="card__title">Ringkasan Hasil Kuis Terbaru</div>
    </div>

    @if($hasilTerbaru->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada hasil kuis</div>
            <div class="empty-state__text">Hasil kuis siswa akan muncul setelah mereka mengerjakan kuis.</div>
        </div>
    @else
        <div class="table-wrap mb-2">
            <table class="table">
                <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kuis</th>
                    <th>Nilai</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($hasilTerbaru as $hasil)
                    <tr>
                        <td>{{ $hasil->siswa->name ?? '-' }}</td>
                        <td>{{ $hasil->kuis->judul }}</td>
                        <td><strong>{{ $hasil->nilai }}</strong></td>
                        <td class="text-success">{{ $hasil->jumlah_benar }}</td>
                        <td class="text-danger">{{ $hasil->jumlah_salah }}</td>
                        <td>{{ $hasil->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card__header">
        <div class="card__title">Akses Cepat</div>
    </div>
    <div class="d-flex gap-2 flex-wrap mb-2">
        <a class="btn btn-fun" href="{{ route('guru.pembelajaran.index') }}">Pembelajaran</a>
        <a class="btn btn-primary" href="{{ route('guru.materi.index') }}">Kelola Materi</a>
        <a class="btn btn-secondary" href="{{ route('guru.modul.index') }}">Kelola Modul</a>
        <a class="btn btn-secondary" href="{{ route('guru.kuis.index') }}">Kelola Kuis</a>
        <a class="btn btn-outline" href="{{ route('guru.progress') }}">Progress Siswa</a>
    </div>
@endsection
