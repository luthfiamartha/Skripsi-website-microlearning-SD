@extends('layouts.app')

@section('title', 'Kelola Materi')

@section('content')
    @include('materi.index', ['prefix' => 'guru'])
@endsection
