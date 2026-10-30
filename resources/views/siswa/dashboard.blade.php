@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
    @php
        $jam = (int) now()->format('G');
        $sapaan = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 19 ? 'Selamat sore' : 'Selamat malam'));
        $inisial = mb_strtoupper(mb_substr($siswa->name, 0, 1));
    @endphp

    {{-- ================= HEADER ================= --}}
    <div class="page-header">
        <div class="page-header__user">
            <span class="avatar" aria-hidden="true">{{ $inisial }}</span>
            <div>
                <h1>{{ $sapaan }}, {{ $siswa->name }}! 👋</h1>
                <p>Yuk lanjutkan belajar IPA hari ini.</p>
            </div>
        </div>
        @if($ringkasan['materiSedang'])
            <a class="btn btn-fun" href="{{ route('siswa.materi.show', $ringkasan['materiSedang']) }}">▶ Lanjutkan Belajar</a>
        @else
            <a class="btn btn-fun" href="{{ route('siswa.mandiri.index') }}">🌱 Mulai Belajar</a>
        @endif
    </div>

    {{-- ================= RINGKASAN PROGRESS ================= --}}
    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">📊 Ringkasan Progress</h3>
        <a class="text-small" href="{{ route('siswa.progress') }}">Lihat detail →</a>
    </div>
    <p class="text-small text-secondary mb-2">Konteks: 🌱 Belajar Mandiri. Progress bersama guru dihitung terpisah di bawah.</p>

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
            <div class="stat-card__label">Progress Belajar</div>
            <div class="stat-card__value">{{ $ringkasan['persentase'] }}%</div>
        </div>
    </div>

    <div class="ctx-grid mt-2">
        <div class="card ctx-card">
            <div class="ctx-card__label">🌱 Progress Belajar Mandiri</div>
            <div class="ctx-card__value">{{ $ringkasan['persentase'] }}%</div>
            <div class="progress">
                <div class="progress__fill {{ $ringkasan['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $ringkasan['persentase'] }}%"></div>
            </div>
            <div class="text-small text-secondary mt-2">{{ $ringkasan['modulSelesai'] }} dari {{ $ringkasan['totalModul'] }} modul • {{ $ringkasan['materiSelesai'] }} materi selesai</div>
        </div>

        @forelse($pembelajaran as $baris)
            <div class="card ctx-card">
                <div class="ctx-card__label">👩‍🏫 {{ $baris['pembelajaran']->nama }}</div>
                <div class="ctx-card__value">{{ $baris['ringkasan']['persentase'] }}%</div>
                <div class="progress">
                    <div class="progress__fill {{ $baris['ringkasan']['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $baris['ringkasan']['persentase'] }}%"></div>
                </div>
                <div class="text-small text-secondary mt-2">Guru: {{ $baris['pembelajaran']->guru->name ?? '-' }} • {{ $baris['ringkasan']['modulSelesai'] }} modul</div>
            </div>
        @empty
            <div class="card ctx-card">
                <div class="ctx-card__label">👩‍🏫 Progress Pembelajaran</div>
                <div class="ctx-card__value">—</div>
                <div class="text-small text-secondary mt-2">Belum ada pembelajaran dengan guru. Kamu tetap bisa belajar mandiri.</div>
            </div>
        @endforelse
    </div>

    {{-- ================= LANJUTKAN BELAJAR ================= --}}
    <h3 class="mb-2 section-title">📚 Lanjutkan Belajar</h3>
    @if($ringkasan['materiSedang'])
        @php
            $itemSedang = collect($ringkasan['daftarMateri'])->firstWhere('materi.id', $ringkasan['materiSedang']->id);
        @endphp
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
                <a class="btn btn-primary btn-sm" href="{{ route('siswa.materi.show', $ringkasan['materiSedang']) }}">Lanjut Belajar ➡</a>
            </div>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state__icon">🎉</div>
            <div class="empty-state__title">Belum ada materi yang sedang dipelajari</div>
            <div class="empty-state__text">Pilih materi pertamamu dan mulai belajar sedikit demi sedikit.</div>
            <a class="btn btn-fun mt-2" href="{{ route('siswa.mandiri.index') }}">🌱 Mulai Belajar</a>
        </div>
    @endif

    {{-- ================= BELAJAR MANDIRI ================= --}}
    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">🌱 Belajar Mandiri</h3>
        <a class="text-small" href="{{ route('siswa.mandiri.index') }}">Lihat semua →</a>
    </div>
    <p class="text-small text-secondary mb-2">Belajar sesuai kecepatanmu tanpa harus bergabung dengan pembelajaran guru.</p>

    <div class="card-grid">
        @forelse($ringkasan['daftarMateri']->take(6) as $i => $item)
            @include('siswa.partials.materi-card', ['item' => $item, 'i' => $i, 'pembelajaran' => null])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">📭</div>
                <div class="empty-state__title">Belum ada materi tersedia</div>
                <div class="empty-state__text">Materi IPA akan segera ditambahkan oleh guru.</div>
            </div>
        @endforelse
    </div>

    {{-- ================= PEMBELAJARAN SAYA ================= --}}
    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">👩‍🏫 Pembelajaran Saya</h3>
        <a class="text-small" href="{{ route('siswa.pembelajaran.index') }}">Lihat semua →</a>
    </div>

    @if($pembelajaran->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum mengikuti pembelajaran dengan guru</div>
            <div class="empty-state__text">Kamu tetap bisa belajar mandiri, atau bergabung menggunakan kode pembelajaran dari guru.</div>
            <a class="btn btn-fun mt-2" href="{{ route('siswa.pembelajaran.index') }}#gabung">🎟️ Gabung Pembelajaran</a>
        </div>
    @else
        <div class="card-grid">
            @foreach($pembelajaran as $baris)
                @php
                    $p = $baris['pembelajaran'];
                    $r = $baris['ringkasan'];
                @endphp
                <div class="card mode-card">
                    <div class="d-flex align-center gap-2">
                        <span class="mode-card__icon">👩‍🏫</span>
                        <div>
                            <div class="mode-card__title">{{ $p->nama }}</div>
                            <div class="text-small text-secondary">Guru: {{ $p->guru->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="progress-label mt-2">
                        <span>Progress</span>
                        <span>{{ $r['persentase'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress__fill {{ $r['persentase'] === 100 ? 'progress__fill--success' : '' }}" style="width: {{ $r['persentase'] }}%"></div>
                    </div>
                    <div class="text-small text-secondary mt-2">
                        {{ $r['materiSelesai'] }} dari {{ $r['daftarMateri']->count() }} materi selesai • {{ $r['modulSelesai'] }} modul
                    </div>
                    <a class="btn btn-primary btn-block mt-2" href="{{ route('siswa.pembelajaran.show', $p) }}">Buka Pembelajaran ➡</a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ================= PROFIL SAYA ================= --}}
    <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
        <h3 class="section-title mb-0">👤 Profil Saya</h3>
        <a class="text-small" href="{{ route('siswa.profil') }}">Lihat profil →</a>
    </div>

    <div class="card">
        <div class="d-flex align-center justify-between flex-wrap gap-3">
            <div class="d-flex align-center gap-2">
                <span class="avatar avatar--lg" aria-hidden="true">{{ $inisial }}</span>
                <div>
                    <div class="materi-card__title">{{ $siswa->name }}</div>
                    <div class="text-small text-secondary">👤 {{ $siswa->username }} • 📧 {{ $siswa->email }}</div>
                    <span class="badge badge-primary" style="margin-top: 6px;">🎓 Siswa</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-primary" href="{{ route('siswa.profil.edit') }}">✏️ Edit Profil</a>
                <a class="btn btn-outline" href="{{ route('siswa.profil') }}">Buka Profil</a>
            </div>
        </div>
    </div>
@endsection
