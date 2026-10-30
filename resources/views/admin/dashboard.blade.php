@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Kelola data sistem pembelajaran dan pantau progres siswa.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary" href="{{ route('admin.materi.create') }}">+ Tambah Materi</a>
            <a class="btn btn-outline" href="{{ route('admin.siswa.index') }}">Data Siswa</a>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__label">Jumlah Siswa</div>
            <div class="stat-card__value">{{ $jumlahSiswa }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Jumlah Materi</div>
            <div class="stat-card__value">{{ $jumlahMateri }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Jumlah Modul</div>
            <div class="stat-card__value">{{ $jumlahModul }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Jumlah Kuis</div>
            <div class="stat-card__value">{{ $jumlahKuis }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Pembelajaran</div>
            <div class="stat-card__value">{{ $jumlahPembelajaran }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Hasil Kuis Tersimpan</div>
            <div class="stat-card__value">{{ $jumlahHasilKuis }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__label">Rata-rata Progress Mandiri</div>
            <div class="stat-card__value">{{ $rataProgress }}%</div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">Ringkasan Progres Siswa — Belajar Mandiri</div>
            <a class="btn btn-outline btn-sm" href="{{ route('admin.progress') }}">Lihat Progres</a>
        </div>
        <div class="progress-label">
            <span>Rata-rata progress belajar mandiri seluruh siswa</span>
            <span>{{ $rataProgress }}%</span>
        </div>
        <div class="progress">
            <div class="progress__fill {{ $rataProgress === 100 ? 'progress__fill--success' : '' }}"
                 style="width: {{ $rataProgress }}%"></div>
        </div>
    </div>

    <div class="card__header">
        <div class="card__title">Pengelolaan Data</div>
    </div>
    <div class="d-flex gap-2 flex-wrap mb-2">
        <a class="btn btn-primary" href="{{ route('admin.materi.index') }}">Kelola Materi</a>
        <a class="btn btn-secondary" href="{{ route('admin.modul.index') }}">Kelola Modul</a>
        <a class="btn btn-secondary" href="{{ route('admin.kuis.index') }}">Kelola Kuis</a>
        <a class="btn btn-outline" href="{{ route('admin.siswa.index') }}">Data Siswa</a>
        <a class="btn btn-outline" href="{{ route('admin.progress') }}">Progres Siswa</a>
    </div>
@endsection
