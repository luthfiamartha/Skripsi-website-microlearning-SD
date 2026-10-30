<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — {{ config('app.name') }}</title>
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
        <div class="auth-card auth-card--wide">
            <div class="auth-brand">
                <div class="auth-brand__logo">🔬</div>
                <h1>Buat Akun Baru</h1>
                <p>Mau belajar jadi <b>Siswa</b> atau mengajar jadi <b>Guru</b>? Pilih dulu ya!</p>
            </div>

            <div class="auth-choices">
                <a class="auth-choice" href="{{ route('register.student') }}">
                    <span class="auth-choice__emoji" aria-hidden="true">👨‍🎓</span>
                    <h2>Daftar sebagai Siswa</h2>
                    <p>Ikuti pembelajaran IPA, baca materi micro-learning, kerjakan kuis, dan pantau progress belajarmu.</p>
                    <span class="btn btn-fun btn-block">Saya Siswa 🚀</span>
                </a>

                <a class="auth-choice auth-choice--guru" href="{{ route('register.teacher') }}">
                    <span class="auth-choice__emoji" aria-hidden="true">👩‍🏫</span>
                    <h2>Daftar sebagai Guru</h2>
                    <p>Buat materi & modul, susun kuis, kelola kelas, dan lihat hasil belajar semua siswa.</p>
                    <span class="btn btn-primary btn-block">Saya Guru 📚</span>
                </a>
            </div>

            <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a> →</p>
        </div>
    </div>
</div>
</body>
</html>
