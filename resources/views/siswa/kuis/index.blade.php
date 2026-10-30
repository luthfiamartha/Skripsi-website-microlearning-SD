@extends('layouts.app')

@section('title', 'Kuis')

@section('content')
    <div class="page-header">
        <div>
            <h1>📝 Kuis</h1>
            <p>Kerjakan kuis sesuai dengan mode belajarmu — hasil Belajar Mandiri dan hasil per pembelajaran tersimpan terpisah.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline" href="{{ route('siswa.hasil.index') }}">🏆 Hasil Kuis</a>
            <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
        </div>
    </div>

    <div class="mode-switch">
        <a class="mode-switch__item {{ request()->query('pembelajaran') === null ? 'is-active' : '' }}"
           href="{{ route('siswa.kuis.index') }}">🌱 Belajar Mandiri</a>
        @foreach($pembelajaran as $baris)
            <a class="mode-switch__item {{ (string) request()->query('pembelajaran') === (string) $baris['pembelajaran']->id ? 'is-active' : '' }}"
               href="{{ route('siswa.kuis.index', ['pembelajaran' => $baris['pembelajaran']->id]) }}">
                👩‍🏫 {{ $baris['pembelajaran']->nama }}
            </a>
        @endforeach
    </div>

    @php
        $idAktif = request()->query('pembelajaran');
        $kuisAktif = $kuisMandiri;
        $judulAktif = '🌱 Kuis Belajar Mandiri';
        $pAktif = null;

        if ($idAktif) {
            foreach ($pembelajaran as $baris) {
                if ((string) $baris['pembelajaran']->id === (string) $idAktif) {
                    $kuisAktif = $baris['kuis'];
                    $judulAktif = '👩‍🏫 Kuis ' . $baris['pembelajaran']->nama;
                    $pAktif = $baris['pembelajaran'];
                    break;
                }
            }
        }
    @endphp

    <div class="card mb-2">
        <div class="card__title">{{ $judulAktif }}</div>
        <p class="text-small text-secondary mb-0">
            @if($pAktif)
                Kuis dari pembelajaran bersama guru {{ $pAktif->guru->name ?? '-' }}.
                Nilai yang kamu peroleh hanya memengaruhi pembelajaran ini.
            @else
                Kuis untuk belajar mandirimu. Nilai tersimpan pada progress belajar mandiri.
            @endif
        </p>
    </div>

    <div class="card-grid">
        @forelse($kuisAktif as $i => $kuisItem)
            @include('siswa.partials.kuis-card', ['kuisItem' => $kuisItem, 'i' => $i, 'pembelajaran' => $pAktif])
        @empty
            <div class="empty-state">
                <div class="empty-state__icon">🧩</div>
                <div class="empty-state__title">Belum ada kuis</div>
                <div class="empty-state__text">Kuis akan muncul setelah guru menambahkannya.</div>
            </div>
        @endforelse
    </div>
@endsection
