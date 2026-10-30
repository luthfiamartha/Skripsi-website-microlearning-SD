<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>
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

    <div class="auth-stack">
        <a class="auth-back" href="{{ route('register') }}">← Pilih jenis akun</a>

        <div class="auth-card">
            <div class="auth-brand">
                <div class="auth-brand__logo">{{ $emoji }}</div>
                <h1>{{ $title }}</h1>
                <p>{{ $subtitle }}</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert--list">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route($actionRoute) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input
                        class="form-input @error('name') is-error @enderror"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="✍️ Contoh: Siti Aminah"
                        autocomplete="name"
                        autofocus
                        required
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input
                        class="form-input @error('username') is-error @enderror"
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="👤 Contoh: siti123"
                        autocomplete="username"
                        required
                    >
                    @error('username')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        class="form-input @error('email') is-error @enderror"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="📧 Contoh: siti@email.com"
                        autocomplete="email"
                        required
                    >
                    @error('email')
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
                        placeholder="🔒 Minimal 8 karakter"
                        autocomplete="new-password"
                        required
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Ulangi Password</label>
                    <input
                        class="form-input @error('password_confirmation') is-error @enderror"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="🔒 Ketik lagi password yang sama"
                        autocomplete="new-password"
                        required
                    >
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-fun btn-block btn-lg" type="submit">🎉 Daftar Sekarang!</button>
            </form>

            <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Login</a> →</p>
        </div>
    </div>
</div>
</body>
</html>
