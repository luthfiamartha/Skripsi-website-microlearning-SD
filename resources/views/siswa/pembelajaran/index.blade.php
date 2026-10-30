@extends('layouts.app')

@section('title', 'Pembelajaran Saya')

@section('content')
    <div class="page-header">
        <div>
            <h1>👩‍🏫 Pembelajaran Saya</h1>
            <p>Daftar pembelajaran yang kamu ikuti bersama guru. Progress dan hasil kuis di sini terpisah dari Belajar Mandiri.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
    </div>

    <div class="card" id="gabung">
        <div class="card__header">
            <div class="card__title">🎟️ Gabung dengan Kode Pembelajaran</div>
        </div>
        <p class="text-small text-secondary mt-0 mb-2">Minta kode pembelajaran kepada gurumu, lalu masukkan di bawah ini.</p>

        <form method="POST" action="{{ route('siswa.pembelajaran.gabung') }}" class="d-flex gap-2 flex-wrap align-end">
            @csrf
            <div class="form-group" style="flex: 1; min-width: 220px; margin-bottom: 0;">
                <label class="form-label" for="kode">Kode Pembelajaran</label>
                <input class="form-input kode-input @error('kode') is-error @enderror" id="kode" name="kode"
                       type="text" value="{{ old('kode') }}" placeholder="Contoh: IPA2026" maxlength="20"
                       autocomplete="off" required>
                @error('kode')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-fun" type="submit">✅ Gabung</button>
        </form>
    </div>

    <h3 class="mb-2 section-title">🏫 Daftar Pembelajaran Diikuti</h3>

    @if($daftar->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum mengikuti pembelajaran dengan guru</div>
            <div class="empty-state__text">Kamu tetap bisa belajar di menu 🌱 Belajar Mandiri, atau bergabung menggunakan kode di atas.</div>
            <a class="btn btn-outline mt-2" href="{{ route('siswa.mandiri.index') }}">🌱 Buka Belajar Mandiri</a>
        </div>
    @else
        <div class="card-grid">
            @foreach($daftar as $baris)
                @php $p = $baris['pembelajaran']; @endphp
                <div class="card mode-card">
                    <div class="d-flex align-center gap-2">
                        <span class="mode-card__icon">👩‍🏫</span>
                        <div>
                            <div class="mode-card__title">{{ $p->nama }}</div>
                            <div class="text-small text-secondary">Guru: {{ $p->guru->name ?? '-' }} • {{ $baris['jumlahSiswa'] }} siswa</div>
                        </div>
                    </div>

                    @if($p->deskripsi)
                        <p class="text-small mt-2 mb-0">{{ $p->deskripsi }}</p>
                    @endif

                    <div class="progress-label mt-2">
                        <span>Progress</span>
                        <span>{{ $baris['persentase'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress__fill {{ $baris['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $baris['persentase'] }}%"></div>
                    </div>

                    <div class="text-small text-secondary mt-2">
                        📚 {{ $baris['materiSelesai'] }} dari {{ $baris['jumlahMateri'] }} materi selesai •
                        📝 {{ $baris['modulSelesai'] }} dari {{ $baris['totalModul'] }} modul
                    </div>

                    <a class="btn btn-primary btn-block mt-2" href="{{ route('siswa.pembelajaran.show', $p) }}">Buka Pembelajaran ➡</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
