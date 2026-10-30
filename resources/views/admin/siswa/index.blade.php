@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Siswa</h1>
            <p>Daftar seluruh siswa — persentase progres menampilkan <strong>Belajar Mandiri</strong> dan jumlah pembelajaran yang diikuti.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('admin.progress') }}">Lihat Progres Siswa</a>
    </div>

    @if($daftar->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada data siswa</div>
            <div class="empty-state__text">Data siswa akan muncul setelah akun siswa tersedia.</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Progres Mandiri</th>
                    <th>Pembelajaran</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($daftar as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row['siswa']->name }}</td>
                        <td>{{ $row['siswa']->username }}</td>
                        <td>{{ $row['siswa']->email }}</td>
                        <td style="min-width: 160px;">
                            <div class="progress">
                                <div class="progress__fill {{ $row['persentase'] === 100 ? 'progress__fill--success' : '' }}"
                                     style="width: {{ $row['persentase'] }}%"></div>
                            </div>
                            <span class="text-small text-secondary">{{ $row['persentase'] }}%</span>
                        </td>
                        <td>
                            @if($row['jumlahPembelajaran'] > 0)
                                <span class="badge badge-primary">{{ $row['jumlahPembelajaran'] }} diikuti</span>
                            @else
                                <span class="badge badge-muted">—</span>
                            @endif
                        </td>
                        <td class="aksi aksi--wrap">
                            <a class="btn btn-outline btn-sm"
                               href="{{ route('admin.progress', ['siswa' => $row['siswa']->id]) }}">
                                Lihat Progress
                            </a>
                            @if($row['jumlahPembelajaran'] > 0)
                                <a class="btn btn-outline btn-sm"
                                   href="{{ route('admin.progress', ['pembelajaran' => $row['pembelajaranPertama']]) }}">
                                    Per Kelas
                                </a>
                            @endif
                            <a class="btn btn-secondary btn-sm"
                               href="{{ route('admin.siswa.edit', $row['siswa']) }}">Edit</a>
                            <form action="{{ route('admin.siswa.destroy', $row['siswa']) }}" method="POST"
                                  data-confirm
                                  data-confirm-title="Apakah kamu yakin ingin menghapus siswa ini?"
                                  data-confirm-text="Data siswa beserta progress belajar, hasil kuis beserta jawabannya, dan keanggotaan pembelajaran yang terkait dapat ikut terdampak.">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @include('partials.confirm-modal')
@endsection
