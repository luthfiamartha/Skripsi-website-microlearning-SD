@extends('layouts.app')

@section('title', 'Detail Materi')

@section('content')
    @include('materi.show', ['prefix' => 'admin'])
@endsection
