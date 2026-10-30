@php
    $ctx = ! empty($pembelajaran) ? ['pembelajaran' => $pembelajaran->id] : [];
    $siap = ($kuisItem['status'] ?? 'terkunci') === 'siap';
    $nilai = $kuisItem['nilai'] ?? null;
@endphp
<div class="card materi-card">
    <div class="materi-card__thumb materi-card__thumb--c4">
        <span class="materi-card__thumb-emoji">📝</span>
    </div>
    <div class="materi-card__title">{{ $kuisItem['kuis']->judul }}</div>
    <div class="materi-card__desc">
        {{ $kuisItem['materi']->judul ?? '-' }} • {{ $kuisItem['jumlah_soal'] }} soal
    </div>
    <div class="materi-card__footer">
        @if($nilai !== null)
            <span class="badge badge-success">🏆 Nilai {{ $nilai }}</span>
        @elseif($siap)
            <span class="badge badge-primary">✨ Siap Dikerjakan</span>
        @else
            <span class="badge badge-warning">🔒 Selesaikan Modul</span>
        @endif

        @if($siap)
            <a class="btn btn-fun btn-sm" href="{{ route('siswa.kuis.show', array_merge([$kuisItem['kuis']->materi_id], $ctx)) }}">
                {{ $nilai !== null ? 'Kerjakan Lagi ▶' : 'Mulai Kuis ▶' }}
            </a>
        @else
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.materi.show', array_merge([$kuisItem['kuis']->materi_id], $ctx)) }}">
                Belajar Dulu ➡
            </a>
        @endif
    </div>
</div>
