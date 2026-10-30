@extends('layouts.app')

@section('title', 'Kelola Kuis')

@section('content')
    @include('kuis.index', ['prefix' => 'admin'])
@endsection
