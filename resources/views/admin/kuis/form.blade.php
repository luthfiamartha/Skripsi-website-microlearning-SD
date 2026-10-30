@extends('layouts.app')

@section('title', $kuis ? 'Edit Kuis' : 'Tambah Kuis')

@section('content')
    @include('kuis.form', ['prefix' => 'admin'])
@endsection
