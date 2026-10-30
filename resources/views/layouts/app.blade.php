<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php
    $user = auth()->user();
@endphp
<div class="app">
    <header class="topbar">
        <button class="topbar__toggle" id="sidebarToggle" type="button" aria-label="Buka menu">☰</button>
        <div class="topbar__brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 3h6M10 3v6.5L5.2 17.4A2 2 0 0 0 6.9 20.5h10.2a2 2 0 0 0 1.7-3.1L14 9.5V3"/>
            </svg>
            <span>🔬 {{ config('app.name') }}</span>
        </div>
        <div class="topbar__user">
            <span class="topbar__name">{{ $user->name }}</span>
            <span class="topbar__role">{{ $user->role }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="topbar__logout" type="submit">👋 Logout</button>
            </form>
        </div>
    </header>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar__title">Menu {{ ucfirst($user->role) }}</div>

        @if($user->role === 'siswa')
            @php
                // Materi/kuis/hasil tanpa parameter pembelajaran = konteks Belajar Mandiri;
                // dengan parameter pembelajaran = konteks Pembelajaran Saya.
                $ctxGuru = (bool) request()->query('pembelajaran');
                $halamanBelajar = request()->routeIs('siswa.mandiri.*')
                    || (request()->routeIs('siswa.materi.*', 'siswa.kuis.*', 'siswa.hasil.*') && ! $ctxGuru);
                $halamanKelas = request()->routeIs('siswa.pembelajaran.*')
                    || (request()->routeIs('siswa.materi.*', 'siswa.kuis.*') && $ctxGuru);
            @endphp
            <a class="sidebar__link {{ request()->routeIs('siswa.dashboard') || request()->routeIs('siswa.profil') ? 'is-active' : '' }}" href="{{ route('siswa.dashboard') }}">🏠 Dashboard</a>
            <a class="sidebar__link {{ $halamanBelajar ? 'is-active' : '' }}" href="{{ route('siswa.mandiri.index') }}">🌱 Belajar Mandiri</a>
            <a class="sidebar__link {{ $halamanKelas ? 'is-active' : '' }}" href="{{ route('siswa.pembelajaran.index') }}">👩‍🏫 Pembelajaran Saya</a>
            <a class="sidebar__link {{ request()->routeIs('siswa.progress') ? 'is-active' : '' }}" href="{{ route('siswa.progress') }}">📊 Progress</a>
        @elseif($user->role === 'guru')
            <a class="sidebar__link {{ request()->routeIs('guru.dashboard') ? 'is-active' : '' }}" href="{{ route('guru.dashboard') }}">🏠 Dashboard</a>
            <a class="sidebar__link {{ request()->routeIs('guru.pembelajaran.*') ? 'is-active' : '' }}" href="{{ route('guru.pembelajaran.index') }}">👩‍🏫 Pembelajaran</a>
            <a class="sidebar__link {{ request()->routeIs('guru.materi.*') ? 'is-active' : '' }}" href="{{ route('guru.materi.index') }}">📖 Kelola Materi</a>
            <a class="sidebar__link {{ request()->routeIs('guru.modul.*') ? 'is-active' : '' }}" href="{{ route('guru.modul.index') }}">📝 Kelola Modul</a>
            <a class="sidebar__link {{ request()->routeIs('guru.kuis.*') ? 'is-active' : '' }}" href="{{ route('guru.kuis.index') }}">❓ Kelola Kuis</a>
            <a class="sidebar__link {{ request()->routeIs('guru.progress') ? 'is-active' : '' }}" href="{{ route('guru.progress') }}">📊 Progress Siswa</a>
        @else
            <a class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
            <a class="sidebar__link {{ request()->routeIs('admin.materi.*') ? 'is-active' : '' }}" href="{{ route('admin.materi.index') }}">📖 Kelola Materi</a>
            <a class="sidebar__link {{ request()->routeIs('admin.modul.*') ? 'is-active' : '' }}" href="{{ route('admin.modul.index') }}">📝 Kelola Modul</a>
            <a class="sidebar__link {{ request()->routeIs('admin.kuis.*') ? 'is-active' : '' }}" href="{{ route('admin.kuis.index') }}">❓ Kelola Kuis</a>
            <a class="sidebar__link {{ request()->routeIs('admin.siswa.*') ? 'is-active' : '' }}" href="{{ route('admin.siswa.index') }}">👧 Data Siswa</a>
            <a class="sidebar__link {{ request()->routeIs('admin.guru.*') ? 'is-active' : '' }}" href="{{ route('admin.guru.index') }}">🧑‍🏫 Data Guru</a>
            <a class="sidebar__link {{ request()->routeIs('admin.progress') ? 'is-active' : '' }}" href="{{ route('admin.progress') }}">📊 Progress Siswa</a>
        @endif

        <div class="sidebar__form">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="sidebar__link" type="submit">👋 Logout</button>
            </form>
        </div>
    </nav>

    <main class="content">
        <div class="content-decor" aria-hidden="true">
            <span>⭐</span><span>🌈</span><span>🐞</span><span>🍀</span><span>✨</span>
        </div>

        @if(session('sukses'))
            <div class="alert alert-success">
                <span>{{ session('sukses') }}</span>
                <button class="alert__close" type="button" aria-label="Tutup">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <span>{{ session('error') }}</span>
                <button class="alert__close" type="button" aria-label="Tutup">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="alert__close" type="button" aria-label="Tutup">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<footer class="footer">
    © {{ date('Y') }} {{ config('app.name') }} — Micro-Learning IPA Kelas III SD
</footer>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
