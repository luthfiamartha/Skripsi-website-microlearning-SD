@extends('layouts.app')

@section('title', $materi ? 'Edit Materi' : 'Tambah Materi')

@section('content')
    @include('materi.form', ['prefix' => 'guru'])
@endsection
