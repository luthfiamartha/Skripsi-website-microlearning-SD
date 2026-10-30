@extends('layouts.app')

@section('title', $modul ? 'Edit Modul' : 'Tambah Modul')

@section('content')
    @include('modul.form', ['prefix' => 'admin'])
@endsection
