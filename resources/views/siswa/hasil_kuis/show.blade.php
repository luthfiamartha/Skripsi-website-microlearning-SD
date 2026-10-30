@extends('layouts.app')

@section('title', 'Detail Hasil Kuis')

@section('content')
    <div class="page-header">
        <div>
            <a class="text-small" href="{{ route('siswa.hasil.index') }}">← Kembali ke hasil kuis</a>
            <h1>🏆 Hasil Kuis</h1>
            <p>{{ $hasil->kuis->judul }} — {{ $hasil->kuis->materi->judul ?? '-' }}</p>
        </div>
    </div>

    <div class="hasil-hero" {{ $hasil->nilai >= 75 ? 'data-celebrate' : '' }}>
        <span class="hasil-hero__emoji">{{ $hasil->nilai >= 75 ? '🎉🌟' : '💪 keep trying!' }}</span>
        <div class="hasil-hero__nilai">{{ $hasil->nilai }}</div>
        <div class="hasil-hero__teks">
            {{ $hasil->nilai >= 75 ? 'Luar biasa! Kamu tuntas! Hebat sekali!' : 'Jangan menyerah, coba lagi ya! Kamu bisa!' }}
        </div>
    </div>

    <div class="card">
        <div class="card__title mb-2">🧮 Informasi Hasil Pengerjaan</div>

        <div class="hasil-grid">
            <div class="hasil-item hasil-item--nilai">
                <div class="hasil-item__value">{{ $hasil->nilai }}</div>
                <div class="hasil-item__label">Nilai</div>
            </div>
            <div class="hasil-item hasil-item--benar">
                <div class="hasil-item__value">{{ $hasil->jumlah_benar }}</div>
                <div class="hasil-item__label">Benar ✅</div>
            </div>
            <div class="hasil-item hasil-item--salah">
                <div class="hasil-item__value">{{ $hasil->jumlah_salah }}</div>
                <div class="hasil-item__label">Salah ❌</div>
            </div>
            <div class="hasil-item">
                <div class="hasil-item__value">{{ $hasil->jumlah_soal }}</div>
                <div class="hasil-item__label">Total Soal</div>
            </div>
        </div>

        <div class="text-small text-secondary">
            🕐 Dikerjakan pada {{ $hasil->created_at->format('d/m/Y H:i') }} •
            📝 Kuis: {{ $hasil->kuis->judul }} •
            📚 Materi: {{ $hasil->kuis->materi->judul ?? '-' }}
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-primary" href="{{ route('siswa.hasil.index') }}">📋 Lihat Semua Hasil</a>
        <a class="btn btn-fun" href="{{ route('siswa.dashboard') }}">🏠 Ke Dashboard</a>
    </div>
@endsection
