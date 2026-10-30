@extends('layouts.app')

@section('title', 'Progress Siswa')

@section('content')
    @include('progress.monitor', ['prefix' => 'guru'])
@endsection
