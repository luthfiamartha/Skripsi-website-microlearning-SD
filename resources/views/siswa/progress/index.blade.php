@extends('layouts.app')

@section('title', 'Progress Belajar')

@section('content')
    <div class="page-header">
        <div>
            <h1>📈 Progress Belajar</h1>
            <p>Progress Belajar Mandiri dan setiap Pembelajaran Guru ditampilkan terpisah — tidak pernah dicampur.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
    </div>

    <div class="chip-filter">
        @foreach($pilihan as $item)
            <a class="chip {{ $item['pembelajaran']?->id === $pembelajaran?->id ? 'is-active' : '' }}"
               href="{{ route('siswa.progress', $item['pembelajaran'] ? ['pembelajaran' => $item['pembelajaran']->id] : []) }}#konteks-{{ $item['pembelajaran']?->id ?? 'mandiri' }}">
                {{ $item['emoji'] }} {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    @foreach($pilihan as $item)
        @php
            $r = $item['ringkasan'];
            $pKelas = $item['pembelajaran'];
            $judul = $pKelas ? 'Progress Pembelajaran — ' . $pKelas->nama : 'Progress Belajar Mandiri';
            $konteksKuis = $pKelas ? 'Kuis Pembelajaran' : 'Kuis Belajar Mandiri';
        @endphp

        <div id="konteks-{{ $pKelas?->id ?? 'mandiri' }}" style="scroll-margin-top: 90px; margin-bottom: 8px;">
            <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2">
                <h3 class="section-title mb-0">{{ $item['emoji'] }} {{ $judul }}</h3>
                <span class="badge {{ $r['persentase'] === 100 ? 'badge-success' : 'badge-primary' }}">{{ $r['persentase'] }}%</span>
            </div>

            {{-- Ringkasan konteks --}}
            <div class="card">
                <div class="card__header">
                    <div class="card__title">{{ $item['emoji'] }} {{ $item['label'] }}</div>
                    <span class="badge {{ $r['persentase'] === 100 ? 'badge-success' : 'badge-primary' }}">{{ $r['persentase'] }}%</span>
                </div>
                <div class="d-flex align-center gap-2 flex-wrap justify-between">
                    <div>
                        <div class="text-small text-secondary">🌟 Persentase Progres Belajar</div>
                        <div class="progress-large">{{ $r['persentase'] }}%</div>
                        <div class="text-small text-secondary">{{ $r['modulSelesai'] }} dari {{ $r['totalModul'] }} modul dipelajari</div>
                    </div>
                    <div style="flex: 1; min-width: 240px;">
                        <div class="progress" style="height: 16px;">
                            <div class="progress__fill {{ $r['persentase'] === 100 ? 'progress__fill--success' : '' }}"
                                 style="width: {{ $r['persentase'] }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="text-small text-secondary mt-2 mb-0">
                    📚 {{ $r['materiSelesai'] }} materi selesai •
                    📝 {{ $r['kuisDikerjakan'] }} kuis dikerjakan •
                    👤 {{ $pKelas ? 'Guru: ' . ($pKelas->guru->name ?? '-') : 'Mandiri tanpa guru' }}
                </div>
            </div>

            {{-- Progress materi per konteks --}}
            <div class="table-wrap mt-2">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>Modul Dipelajari</th>
                        <th>Progress Materi</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($r['daftarMateri'] as $i => $baris)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $baris['materi']->judul }}</td>
                            <td>{{ $baris['modul_selesai'] }} / {{ $baris['modul_count'] }}</td>
                            <td style="min-width: 160px;">
                                <div class="progress">
                                    <div class="progress__fill {{ $baris['persentase'] === 100 ? 'progress__fill--success' : '' }}"
                                         style="width: {{ $baris['persentase'] }}%"></div>
                                </div>
                                <span class="text-small text-secondary">{{ $baris['persentase'] }}%</span>
                            </td>
                            <td>
                                @if($baris['status'] === 'selesai')
                                    <span class="badge badge-success">✓ Materi Selesai</span>
                                @elseif($baris['persentase'] > 0)
                                    <span class="badge badge-info">Sedang Dipelajari</span>
                                @else
                                    <span class="badge badge-warning">○ Belum Dipelajari</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state mb-0">
                                    <div class="empty-state__title">Belum ada data progress</div>
                                    <div class="empty-state__text">Mulai pelajari materi untuk membangun progress belajarmu.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Kuis per konteks --}}
            <div class="card mt-2">
                <div class="card__header">
                    <div class="card__title">📝 {{ $konteksKuis }}</div>
                    <span class="badge badge-primary">{{ $item['kuisSelesai'] }} dari {{ $item['kuis']->count() }} selesai</span>
                </div>

                @if($item['kuis']->isEmpty())
                    <p class="text-small text-secondary mb-0">
                        @if($pKelas)
                            Belum ada kuis dari guru.
                        @else
                            Belum ada kuis belajar mandiri.
                        @endif
                    </p>
                @else
                    <div class="table-wrap mb-0">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kuis</th>
                                <th>Materi</th>
                                <th>Soal</th>
                                <th>Nilai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item['kuis'] as $i => $kuisItem)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $kuisItem['kuis']->judul }}</td>
                                    <td>{{ $kuisItem['materi']->judul ?? '-' }}</td>
                                    <td>{{ $kuisItem['jumlah_soal'] }}</td>
                                    <td>
                                        @if($kuisItem['nilai'] !== null)
                                            <span class="badge badge-success">🏆 Nilai {{ $kuisItem['nilai'] }}</span>
                                        @elseif($kuisItem['status'] === 'siap')
                                            <span class="badge badge-primary">Belum dikerjakan</span>
                                        @else
                                            <span class="badge badge-warning">🔒 Selesaikan Modul</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Bagian pembelajaran guru ketika siswa belum bergabung --}}
    @if($pilihan->count() === 1)
        <div class="d-flex align-center justify-between flex-wrap gap-2 mb-2 mt-3">
            <h3 class="section-title mb-0">👩‍🏫 Progress Pembelajaran</h3>
        </div>
        <div class="empty-state">
            <div class="empty-state__icon">🏫</div>
            <div class="empty-state__title">Belum ada pembelajaran dengan guru.</div>
            <div class="empty-state__text">Kamu tetap bisa belajar secara mandiri, atau bergabung menggunakan kode pembelajaran dari guru.</div>
            <div class="d-flex gap-2 flex-wrap justify-center mt-2">
                <a class="btn btn-fun" href="{{ route('siswa.pembelajaran.index') }}#gabung">🎟️ Gabung Pembelajaran</a>
                <a class="btn btn-outline" href="{{ route('siswa.mandiri.index') }}">🌱 Belajar Mandiri</a>
            </div>
        </div>
    @endif
@endsection
