@php
    $ctx = ! empty($pembelajaran) ? ['pembelajaran' => $pembelajaran->id] : [];
    $judulMateri = $item['materi']->judul;
    $emojiMateri = '🔬';
    if (stripos($judulMateri, 'hewan') !== false) $emojiMateri = '🐾';
    elseif (stripos($judulMateri, 'tumbuhan') !== false) $emojiMateri = '🌱';
    elseif (stripos($judulMateri, 'air') !== false) $emojiMateri = '💧';
    $warnaMateri = 'c' . (((int) ($i ?? 0) % 4) + 1);
@endphp
<div class="card materi-card">
    <div class="materi-card__thumb materi-card__thumb--{{ $warnaMateri }}">
        <span class="materi-card__thumb-emoji">{{ $emojiMateri }}</span>
    </div>
    <div class="materi-card__title">{{ $judulMateri }}</div>
    <div class="materi-card__desc">{{ \Illuminate\Support\Str::limit($item['materi']->deskripsi ?? '', 90) }}</div>
    <div class="text-small text-secondary">
        📝 {{ $item['materi']->modul_count ?? 0 }} modul • ✅ {{ $item['modul_selesai'] ?? 0 }} dipelajari
    </div>
    <div class="progress">
        <div class="progress__fill {{ ($item['persentase'] ?? 0) === 100 ? 'progress__fill--success' : '' }}"
             style="width: {{ $item['persentase'] ?? 0 }}%"></div>
    </div>
    <div class="materi-card__footer">
        @if(($item['status'] ?? 'belum') === 'selesai')
            <span class="badge badge-success">🏆 Selesai</span>
        @elseif(($item['persentase'] ?? 0) > 0)
            <span class="badge badge-info">🔥 {{ $item['persentase'] }}%</span>
        @else
            <span class="badge badge-warning">🆕 Baru</span>
        @endif
        <a class="btn btn-primary btn-sm" href="{{ route('siswa.materi.show', array_merge([$item['materi']], $ctx)) }}">
            {{ ($item['status'] ?? 'belum') === 'selesai' ? 'Lihat 👀' : (($item['persentase'] ?? 0) > 0 ? 'Lanjut ➡' : 'Mulai ▶') }}
        </a>
    </div>
</div>
