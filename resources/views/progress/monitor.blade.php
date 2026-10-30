@php
    $paramsKonteks = ! empty($pembelajaranAktif) ? ['pembelajaran' => $pembelajaranAktif->id] : [];
    $judulHalaman = $judul ?? 'Progress Siswa';
    $subjudulHalaman = $subjudul ?? 'Pantau progress belajar siswa.';
    $pesanList = $pesanKosong ?? 'Data akan muncul setelah ada siswa terdaftar.';
@endphp

<div class="page-header">
    <div>
        <h1>{{ $judulHalaman }}</h1>
        <p>{{ $subjudulHalaman }}</p>
    </div>
    @if(! empty($labelKonteks))
        <span class="badge badge-primary">📍 {{ $labelKonteks }}</span>
    @endif
</div>

@if($pilihan && $pilihan->count() >= 1)
    <div class="chip-filter">
        @foreach($pilihan as $item)
            <a class="chip {{ ! empty($item['aktif']) ? 'is-active' : '' }}"
               href="{{ route($prefix . '.progress', $item['params'] ?? []) }}">
                {{ $item['emoji'] ?? '📍' }} {{ $item['label'] }}
            </a>
        @endforeach
    </div>
@endif

@if(! $siswaAktif)
    @if($ringkasan->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">📭</div>
            <div class="empty-state__title">{{ $pesanKosong ? 'Belum ada data' : 'Belum ada data progress siswa' }}</div>
            <div class="empty-state__text">{{ $pesanList }}</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Username</th>
                    <th>Materi Selesai</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ringkasan as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row['siswa']->name }}</td>
                        <td>{{ $row['siswa']->username }}</td>
                        <td>{{ $row['materi_selesai'] }}</td>
                        <td style="min-width: 170px;">
                            <div class="progress">
                                <div class="progress__fill {{ $row['persentase'] === 100 ? 'progress__fill--success' : '' }}"
                                     style="width: {{ $row['persentase'] }}%"></div>
                            </div>
                            <span class="text-small text-secondary">{{ $row['persentase'] }}%</span>
                        </td>
                        <td class="aksi">
                            <a class="btn btn-outline btn-sm"
                               href="{{ route($prefix . '.progress', array_merge($paramsKonteks, ['siswa' => $row['siswa']->id])) }}">
                                Lihat Progress
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@else
    <div class="page-header">
        <div>
            <a class="text-small" href="{{ route($prefix . '.progress', $paramsKonteks) }}">← Kembali ke daftar siswa</a>
            <h1>{{ $judulHalaman }}</h1>
            <p>
                Detail progress belajar {{ $siswaAktif->name }} ({{ $siswaAktif->username }})
                @if(! empty($labelKonteks)) pada <strong>{{ $labelKonteks }}</strong> @endif
            </p>
        </div>
        @php
            $totalSelesai = $detailProgress->where('status', 'selesai')->count();
            $totalBaris = $detailProgress->count();
            $persentaseDetail = $totalBaris > 0 ? round($totalSelesai / $totalBaris * 100) : 0;
        @endphp
        <span class="badge {{ $totalBaris > 0 && $persentaseDetail === 100 ? 'badge-success' : 'badge-primary' }}">
            {{ $persentaseDetail }}% Materi Selesai
        </span>
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">Materi yang Telah dan Belum Dipelajari</div>
            @if(! empty($labelKonteks))
                <span class="badge badge-outline">{{ $labelKonteks }}</span>
            @endif
        </div>

        @if($detailProgress->isEmpty())
            <div class="empty-state mb-0">
                <div class="empty-state__title">Belum ada materi</div>
                <div class="empty-state__text">Materi belum tersedia dalam sistem.</div>
            </div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>Modul Dipelajari</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($detailProgress as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item['materi']->judul }}</td>
                            <td>{{ $item['modul_selesai'] }} / {{ $item['materi']->modul_count }}</td>
                            <td style="min-width: 150px;">
                                <div class="progress">
                                    <div class="progress__fill {{ $item['persentase'] === 100 ? 'progress__fill--success' : '' }}"
                                         style="width: {{ $item['persentase'] }}%"></div>
                                </div>
                                <span class="text-small text-secondary">{{ $item['persentase'] }}%</span>
                            </td>
                            <td>
                                @if($item['status'] === 'selesai')
                                    <span class="badge badge-success">Sudah Dipelajari</span>
                                @elseif($item['persentase'] > 0)
                                    <span class="badge badge-info">Sedang Dipelajari</span>
                                @else
                                    <span class="badge badge-warning">Belum Dipelajari</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card__header">
            <div class="card__title">Hasil Kuis Siswa</div>
            @if(! empty($labelKonteks))
                <span class="badge badge-outline">{{ $labelKonteks }}</span>
            @endif
        </div>

        @if($hasilSiswa->isEmpty())
            <div class="empty-state mb-0">
                <div class="empty-state__title">Belum ada hasil kuis</div>
                <div class="empty-state__text">Siswa ini belum mengerjakan kuis.</div>
            </div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Kuis</th>
                        <th>Materi</th>
                        <th>Nilai</th>
                        <th>Benar</th>
                        <th>Salah</th>
                        <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($hasilSiswa as $hasil)
                        <tr>
                            <td>{{ $hasil->kuis->judul }}</td>
                            <td>{{ $hasil->kuis->materi->judul ?? '-' }}</td>
                            <td><strong>{{ $hasil->nilai }}</strong></td>
                            <td class="text-success">{{ $hasil->jumlah_benar }}</td>
                            <td class="text-danger">{{ $hasil->jumlah_salah }}</td>
                            <td>{{ $hasil->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif
