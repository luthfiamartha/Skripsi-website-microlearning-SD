@extends('layouts.app')

@section('title', $materi->judul)

@section('content')
    @php $ctx = $pembelajaran ? ['pembelajaran' => $pembelajaran->id] : []; @endphp

    <div class="page-header">
        <div>
            <a class="text-small" href="{{ route('siswa.materi.index', $ctx) }}">← Kembali ke daftar materi</a>
            <h1>📖 {{ $materi->judul }}</h1>
            <p>{{ $materi->deskripsi }}</p>
        </div>
        @if($progress && $progress->status === 'selesai')
            <span class="badge badge-success">🏆 Materi Selesai</span>
        @elseif($progress && $progress->persentase > 0)
            <span class="badge badge-info">🔥 Sedang Dipelajari — {{ $progress->persentase }}%</span>
        @else
            <span class="badge badge-warning">🆕 Belum Dipelajari</span>
        @endif
    </div>

    <div class="mode-banner {{ $pembelajaran ? 'mode-banner--kelas' : 'mode-banner--mandiri' }}">
        @if($pembelajaran)
            <span class="mode-banner__icon">👩‍🏫</span>
            <div>
                <strong>{{ $pembelajaran->nama }}</strong>
                <div class="text-small">Progress materi ini dicatat pada pembelajaran bersama guru {{ $pembelajaran->guru->name ?? '-' }}.</div>
            </div>
            <a class="btn btn-outline btn-sm" href="{{ route('siswa.pembelajaran.show', $pembelajaran) }}">Buka Pembelajaran</a>
        @else
            <span class="mode-banner__icon">🌱</span>
            <div>
                <strong>Belajar Mandiri</strong>
                <div class="text-small">Progress materi ini hanya milikmu sendiri.</div>
            </div>
        @endif
    </div>

    @if($modulList->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">📭</div>
            <div class="empty-state__title">Belum ada modul pada materi ini</div>
            <div class="empty-state__text">Modul micro-learning akan segera ditambahkan oleh guru.</div>
        </div>
    @else
        <ul class="stepper">
            @foreach($modulList as $i => $modul)
                @php
                    $selesai = $progress && $modul->urutan <= $progress->modul_selesai;
                    $terkunci = $progress ? $modul->urutan > $progress->modul_selesai + 1 : $i > 0;
                    $aktif = $modulAktif && $modul->id === $modulAktif->id;
                @endphp
                <li class="stepper__item {{ $selesai ? 'is-done' : '' }} {{ $aktif ? 'is-current' : '' }} {{ $terkunci ? 'is-locked' : '' }}">
                    @if($terkunci)
                        🔒 {{ $i + 1 }}. {{ \Illuminate\Support\Str::limit($modul->judul, 22) }}
                    @elseif($selesai)
                        ✅ {{ $i + 1 }}. {{ \Illuminate\Support\Str::limit($modul->judul, 22) }}
                    @else
                        <a href="{{ route('siswa.materi.show', array_merge([$materi, 'modul' => $modul->id], $ctx)) }}" style="color: inherit; text-decoration: none;">
                            ▶ {{ $i + 1 }}. {{ \Illuminate\Support\Str::limit($modul->judul, 22) }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>

        @if($modulAktif)
            <div class="card">
                <div class="card__header">
                    <div>
                        <div class="text-small text-secondary">📝 Modul {{ $indexAktif + 1 }} dari {{ $totalModul }}</div>
                        <div class="card__title">{{ $modulAktif->judul }}</div>
                    </div>
                    @if($modulAktif->urutan <= ($progress->modul_selesai ?? 0))
                        <span class="badge badge-success">✅ Sudah Dipelajari</span>
                    @else
                        <span class="badge badge-warning">📖 Belum Selesai</span>
                    @endif
                </div>

                <div class="modul-content">
                    @foreach(preg_split('/\r?\n/', trim($modulAktif->konten)) as $paragraf)
                        @if(trim($paragraf) !== '')
                            <p>{{ $paragraf }}</p>
                        @endif
                    @endforeach
                </div>

                @if($modulAktif->gambar)
                    <div class="modul-gambar">
                        <img src="{{ asset('uploads/' . $modulAktif->gambar) }}" alt="Visualisasi {{ $modulAktif->judul }}">
                    </div>
                @endif

                <div class="modul-nav">
                    @if($indexAktif > 0)
                        <a class="btn btn-outline"
                           href="{{ route('siswa.materi.show', array_merge([$materi, 'modul' => $modulList[$indexAktif - 1]->id], $ctx)) }}">
                            ← Sebelumnya
                        </a>
                    @else
                        <span></span>
                    @endif

                    @if($modulAktif->urutan > ($progress->modul_selesai ?? 0))
                        <form action="{{ route('siswa.materi.selesai', array_merge([$materi], $ctx)) }}" method="POST">
                            @csrf
                            <input type="hidden" name="modul_id" value="{{ $modulAktif->id }}">
                            <button class="btn btn-success btn-lg" type="submit">
                                {{ $indexAktif + 1 < $totalModul ? '✅ Tandai Selesai & Lanjut ➡' : '🏆 Tandai Selesai' }}
                            </button>
                        </form>
                    @elseif($indexAktif + 1 < $totalModul)
                        <a class="btn btn-primary btn-lg"
                           href="{{ route('siswa.materi.show', array_merge([$materi, 'modul' => $modulList[$indexAktif + 1]->id], $ctx)) }}">
                            Berikutnya ➡
                        </a>
                    @else
                        <span class="badge badge-success">🎉 Seluruh modul selesai</span>
                    @endif
                </div>
            </div>

            @if($progress && $progress->status === 'selesai' && $kuis)
                <div class="card">
                    <div class="card__header">
                        <div class="card__title">❓ Kuis Evaluasi</div>
                        <span class="badge badge-primary">✨ Siap Dikerjakan</span>
                    </div>
                    <p class="text-secondary text-small">Seluruh modul telah kamu selesaikan. Uji pemahamanmu dengan mengerjakan kuis berikut! 💪</p>
                    <a class="btn btn-fun" href="{{ route('siswa.kuis.show', array_merge([$materi], $ctx)) }}">🎯 Kerjakan Kuis Sekarang!</a>
                </div>
            @endif
        @endif
    @endif
@endsection
