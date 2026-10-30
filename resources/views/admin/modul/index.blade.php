@extends('layouts.app')

@section('title', 'Kelola Modul')

@section('content')
    @include('modul.index', ['prefix' => 'admin'])
@endsection
