@extends('layouts.app')

@section('title', 'Hasil Kuis')

@section('content')
    <div class="page-header">
        <div>
            <h1>Hasil Kuis</h1>
            <p>Daftar hasil pengerjaan kuis milikmu — hasil Belajar Mandiri dan hasil per pembelajaran ditandai terpisah.</p>
        </div>
    </div>

    @if($hasil->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">◻</div>
            <div class="empty-state__title">Belum ada hasil kuis</div>
            <div class="empty-state__text">Hasil kuis akan muncul setelah kamu mengerjakan kuis.</div>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kuis</th>
                    <th>Materi</th>
                    <th>Mode Belajar</th>
                    <th>Nilai</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($hasil as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->kuis->judul }}</td>
                        <td>{{ $row->kuis->materi->judul ?? '-' }}</td>
                        <td>
                            @if($row->pembelajaran)
                                <span class="badge badge-primary">👩‍🏫 {{ $row->pembelajaran->nama }}</span>
                            @else
                                <span class="badge badge-info">🌱 Mandiri</span>
                            @endif
                        </td>
                        <td><strong>{{ $row->nilai }}</strong></td>
                        <td class="text-success">{{ $row->jumlah_benar }}</td>
                        <td class="text-danger">{{ $row->jumlah_salah }}</td>
                        <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                        <td class="aksi">
                            <a class="btn btn-outline btn-sm" href="{{ route('siswa.hasil.show', $row) }}">Lihat</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
