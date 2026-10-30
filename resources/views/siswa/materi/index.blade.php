@extends('layouts.app')

@section('title', 'Materi IPA')

@section('content')
    @php $ctx = $pembelajaran ? ['pembelajaran' => $pembelajaran->id] : []; @endphp

    <div class="page-header">
        <div>
            <h1>📚 Materi IPA</h1>
            <p>Pilih materi untuk mulai belajar — belajar sedikit demi sedikit, jadi hebat! ⭐</p>
        </div>
        <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
    </div>

    <div class="mode-banner {{ $pembelajaran ? 'mode-banner--kelas' : 'mode-banner--mandiri' }}">
        @if($pembelajaran)
            <span class="mode-banner__icon">👩‍🏫</span>
            <div>
                <strong>{{ $pembelajaran->nama }}</strong>
                <div class="text-small">Bersama guru {{ $pembelajaran->guru->name ?? '-' }} — progress di sini terpisah dari belajar mandiri.</div>
            </div>
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.pembelajaran.show', $pembelajaran) }}">Buka Pembelajaran</a>
        @else
            <span class="mode-banner__icon">🌱</span>
            <div>
                <strong>Belajar Mandiri</strong>
                <div class="text-small">Progress materi ini hanya milikmu sendiri, tidak dicampur dengan pembelajaran kelas.</div>
            </div>
            @if($siswa->pembelajaranDiikuti()->exists())
                <a class="btn btn-outline btn-sm" href="{{ route('siswa.pembelajaran.index') }}">Pembelajaran Saya</a>
            @endif
        @endif
    </div>

    <div class="card-grid">
        @forelse($daftarMateri as $i => $item)
            @include('siswa.partials.materi-card', ['item' => $item, 'i' => $i, 'pembelajaran' => $pembelajaran])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">📭</div>
                <div class="empty-state__title">Belum ada materi tersedia</div>
                <div class="empty-state__text">Materi IPA akan segera ditambahkan oleh guru.</div>
            </div>
        @endforelse
    </div>
@endsection
