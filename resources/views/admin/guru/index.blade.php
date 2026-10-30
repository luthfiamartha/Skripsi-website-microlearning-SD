@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Guru</h1>
            <p>Kelola data guru yang terdaftar dalam platform.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>

    @if($guruList->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada data guru.</div>
            <div class="empty-state__text">Data guru akan muncul setelah akun guru tersedia.</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Pembelajaran</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($guruList as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row['guru']->name }}</td>
                        <td>{{ $row['guru']->username }}</td>
                        <td>{{ $row['guru']->email }}</td>
                        <td>
                            @if($row['jumlahPembelajaran'] > 0)
                                <span class="badge badge-primary">{{ $row['jumlahPembelajaran'] }} pembelajaran</span>
                                <div class="text-small text-secondary">{{ $row['daftarPembelajaran']->implode(', ') }}</div>
                            @else
                                <span class="badge badge-muted">—</span>
                            @endif
                        </td>
                        <td class="aksi aksi--wrap">
                            <a class="btn btn-outline btn-sm"
                               href="{{ route('admin.guru.edit', $row['guru']) }}">Edit</a>
                            <form action="{{ route('admin.guru.destroy', $row['guru']) }}" method="POST"
                                  data-confirm
                                  data-confirm-title="Apakah kamu yakin ingin menghapus guru ini?"
                                  data-confirm-text="Akun guru beserta pembelajaran yang dibuatnya, anggota kelas, progress, dan hasil kuis pada pembelajaran tersebut ikut terdampak. Data materi, modul, dan kuis global tidak ikut terhapus.">
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
