@extends('layouts.app')

@section('title', 'Pembelajaran')

@section('content')
    <div class="page-header">
        <div>
            <h1>👩‍🏫 Pembelajaran</h1>
            <p>Buat pembelajaran, bagikan kode-nya kepada siswa, lalu pantau progress hanya dari siswa yang bergabung.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('guru.progress') }}">📊 Progress Siswa</a>
    </div>

    <div class="card mb-2">
        <div class="card__header">
            <div class="card__title">➕ Buat Pembelajaran Baru</div>
        </div>

        <form method="POST" action="{{ route('guru.pembelajaran.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Pembelajaran</label>
                    <input class="form-input @error('nama') is-error @enderror" id="nama" name="nama"
                           type="text" value="{{ old('nama') }}" placeholder="Contoh: IPA Kelas 3A" maxlength="100" required>
                    @error('nama')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi <span class="text-secondary">(opsional)</span></label>
                    <input class="form-input @error('deskripsi') is-error @enderror" id="deskripsi" name="deskripsi"
                           type="text" value="{{ old('deskripsi') }}" placeholder="Contoh: Pertemuan 1 — Daur Air" maxlength="255">
                    @error('deskripsi')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button class="btn btn-fun" type="submit">✅ Buat Pembelajaran</button>
        </form>
    </div>

    <h3 class="mb-2 section-title">🏫 Daftar Pembelajaran</h3>

    @if($daftar->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum ada pembelajaran</div>
            <div class="empty-state__text">Buat pembelajaran pertamamu, lalu bagikan kode-nya kepada siswa agar bisa bergabung.</div>
        </div>
    @else
        <div class="card-grid">
            @foreach($daftar as $baris)
                @php $p = $baris['pembelajaran']; @endphp
                <div class="card mode-card">
                    <div class="d-flex align-center justify-between flex-wrap gap-2">
                        <div>
                            <div class="mode-card__title">{{ $p->nama }}</div>
                            <div class="text-small text-secondary">Dibuat {{ $p->created_at->format('d/m/Y') }}</div>
                        </div>
                        <span class="badge badge-primary">{{ $baris['jumlahSiswa'] }} siswa</span>
                    </div>

                    @if($p->deskripsi)
                        <p class="text-small mt-2 mb-0">{{ $p->deskripsi }}</p>
                    @endif

                    <div class="kode-box kode-box--lg mt-2">
                        <span>
                            <span class="text-small text-secondary">Kode gabung siswa</span>
                            <strong>{{ $p->kode }}</strong>
                        </span>
                        <button class="btn btn-outline btn-sm js-copy" type="button" data-copy="{{ $p->kode }}">Salin</button>
                    </div>

                    <div class="progress-label mt-2">
                        <span>Rata-rata progress siswa</span>
                        <span>{{ $baris['persentase'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress__fill {{ $baris['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $baris['persentase'] }}%"></div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('guru.progress', ['pembelajaran' => $p->id]) }}">📊 Progress Siswa</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
