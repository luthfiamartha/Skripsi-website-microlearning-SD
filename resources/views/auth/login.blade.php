<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-page">
    <div class="auth-bubble auth-bubble--1"></div>
    <div class="auth-bubble auth-bubble--2"></div>
    <div class="auth-bubble auth-bubble--3"></div>
    <div class="auth-bubble auth-bubble--4"></div>
    <div class="auth-bubble auth-bubble--5"></div>

    <span class="auth-emoji auth-emoji--1">🔬</span>
    <span class="auth-emoji auth-emoji--2">🌱</span>
    <span class="auth-emoji auth-emoji--3">🦋</span>
    <span class="auth-emoji auth-emoji--4">⭐</span>

    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-brand__logo">🔬</div>
            <h1>{{ config('app.name') }}</h1>
            <p>Belajar IPA jadi seru & mudah! 🎈</p>
            <p>Micro-Learning Kelas III SD</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="login">Email atau Username</label>
                <input
                    class="form-input @error('login') is-error @enderror"
                    type="text"
                    id="login"
                    name="login"
                    value="{{ old('login') }}"
                    placeholder="✍️ Masukkan email atau username"
                    autofocus
                    required
                >
                @error('login')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    class="form-input @error('password') is-error @enderror"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="🔒 Masukkan password"
                    required
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button class="btn btn-fun btn-block btn-lg" type="submit">🚀 Masuk & Belajar!</button>
        </form>

        <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a> →</p>
    </div>
</div>
</body>
</html>
