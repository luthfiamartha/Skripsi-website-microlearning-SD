@extends('layouts.app')

@section('title', 'Kuis ' . $materi->judul)

@section('content')
    @php $ctx = $pembelajaran ? ['pembelajaran' => $pembelajaran->id] : []; @endphp

    <div class="page-header">
        <div>
            <a class="text-small" href="{{ route('siswa.materi.show', array_merge([$materi], $ctx)) }}">← Kembali ke materi</a>
            <h1>❓ {{ $kuis->judul }}</h1>
            <p>{{ $materi->judul }} • {{ $kuis->soal->count() }} soal — Baca baik-baik ya! 🌟</p>
        </div>
        <span class="badge {{ $pembelajaran ? 'badge-primary' : 'badge-info' }}">
            {{ $pembelajaran ? '👩‍🏫 ' . $pembelajaran->nama : '🌱 Belajar Mandiri' }}
        </span>
    </div>

    <form action="{{ route('siswa.kuis.submit', array_merge([$materi], $ctx)) }}" method="POST" id="form-kuis">
        @csrf

        @foreach($kuis->soal as $i => $soal)
            <div class="soal-card" data-soal-id="{{ $soal->id }}">
                <div class="d-flex align-center">
                    <span class="soal-card__nomor">{{ $i + 1 }}</span>
                    <span class="text-small text-secondary">Soal {{ $i + 1 }} dari {{ $kuis->soal->count() }}</span>
                </div>

                <div class="soal-card__pertanyaan">{{ $soal->pertanyaan }}</div>

                <div class="pilihan-list">
                    @foreach(['a' => $soal->pilihan_a, 'b' => $soal->pilihan_b, 'c' => $soal->pilihan_c, 'd' => $soal->pilihan_d] as $key => $teks)
                        <label class="pilihan">
                            <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $key }}">
                            <span class="pilihan__label">{{ strtoupper($key) }}.</span>
                            <span>{{ $teks }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary btn-lg" type="submit">Submit Jawaban</button>
            <a class="btn btn-outline btn-lg" href="{{ route('siswa.materi.show', array_merge([$materi], $ctx)) }}">Batal</a>
        </div>
    </form>
@endsection
