<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda — {{ config('app.name') }}</title>
    <meta name="description" content="Platform pembelajaran IPA interaktif berbasis web dengan pendekatan micro-learning untuk siswa sekolah dasar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="lp-body">

<header class="lp-nav" id="lpNav">
    <div class="lp-nav__inner">
        <a class="lp-brand" href="{{ url('/') }}">
            <span class="lp-brand__logo" aria-hidden="true">🔬</span>
            <span class="lp-brand__text">{{ config('app.name') }}</span>
        </a>

        <button class="lp-nav__toggle" id="lpNavToggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="lpNavMenu">☰</button>

        <nav class="lp-nav__menu" id="lpNavMenu" aria-label="Menu utama">
            <a class="lp-nav__link" href="#beranda">Beranda</a>
            <a class="lp-nav__link" href="#tentang">Tentang</a>
            <a class="lp-nav__link" href="#fitur">Fitur</a>
            <a class="lp-nav__link" href="#cara-belajar">Cara Belajar</a>
            <div class="lp-nav__actions">
                <a class="btn btn-sm btn-outline" href="{{ route('register') }}">Daftar</a>
                <a class="btn btn-sm btn-primary" href="{{ route('login') }}">Login</a>
            </div>
        </nav>
    </div>
</header>

<main>

    {{-- ============ HERO ============ --}}
    <section class="lp-hero" id="beranda">
        <div class="lp-bubble lp-bubble--1" aria-hidden="true"></div>
        <div class="lp-bubble lp-bubble--2" aria-hidden="true"></div>
        <div class="lp-bubble lp-bubble--3" aria-hidden="true"></div>

        <div class="lp-container lp-hero__grid">
            <div class="lp-hero__text">
                <span class="lp-pill">🌱 Micro-learning Kelas III SD</span>
                <h1 class="lp-hero__title">Belajar IPA Jadi Lebih Mudah dan Menyenangkan!</h1>
                <p class="lp-hero__subtitle">Pelajari materi IPA kelas III SD melalui pembelajaran singkat, bertahap, dan interaktif dengan pendekatan micro-learning.</p>

                <div class="lp-hero__actions">
                    <a class="btn btn-fun" href="{{ route('login') }}">Mulai Belajar</a>
                    <a class="btn btn-outline" href="#tentang">Pelajari Lebih Lanjut</a>
                </div>

                <ul class="lp-hero__points">
                    <li>📚 Materi dipotong jadi modul singkat</li>
                    <li>❓ Kuis di akhir setiap materi</li>
                    <li>📈 Progress belajar terpantau</li>
                </ul>
            </div>

            <div class="lp-hero__visual">
                <div class="lp-hero__frame">
                    <img src="{{ asset('uploads/makhluk-hidup-hero.png') }}" alt="Ilustrasi materi makhluk hidup dan lingkungan">
                </div>

                <div class="lp-float lp-float--1" aria-hidden="true">
                    <span class="lp-float__icon">📗</span>
                    <span>Modul 1 selesai</span>
                </div>
                <div class="lp-float lp-float--2" aria-hidden="true">
                    <span class="lp-float__icon">⭐</span>
                    <span>Kuis 75</span>
                </div>
                <div class="lp-float lp-float--3" aria-hidden="true">
                    <span class="lp-float__icon">📈</span>
                    <span>Progress 67%</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TENTANG ============ --}}
    <section class="lp-section" id="tentang">
        <div class="lp-container lp-about">
            <div class="lp-section__head">
                <span class="lp-pill lp-pill--soft">Tentang</span>
                <h2>Tentang Platform</h2>
            </div>

            <div class="lp-about__grid">
                <p class="lp-about__lead">Platform ini merupakan media pembelajaran IPA berbasis web untuk membantu siswa kelas III SD mempelajari dan mengulang materi IPA secara bertahap.</p>
                <p class="lp-about__lead">Materi menggunakan pendekatan micro-learning sehingga materi dibagi menjadi beberapa modul singkat dan sederhana.</p>
            </div>
        </div>
    </section>

    {{-- ============ FITUR ============ --}}
    <section class="lp-section lp-section--alt" id="fitur">
        <div class="lp-container">
            <div class="lp-section__head">
                <span class="lp-pill lp-pill--soft">Fitur</span>
                <h2>Fitur Utama</h2>
                <p class="lp-section__sub">Fitur yang tersedia di dalam platform pembelajaran IPA.</p>
            </div>

            <div class="lp-grid lp-grid--4">
                <article class="lp-card">
                    <span class="lp-card__icon lp-card__icon--1" aria-hidden="true">📖</span>
                    <h3>Materi Micro-learning</h3>
                    <p>Materi IPA dibagi menjadi modul-modul singkat agar siswa dapat belajar secara bertahap.</p>
                </article>

                <article class="lp-card">
                    <span class="lp-card__icon lp-card__icon--2" aria-hidden="true">🖼️</span>
                    <h3>Visualisasi Pembelajaran</h3>
                    <p>Materi dapat dilengkapi gambar atau visualisasi untuk membantu siswa memahami materi.</p>
                </article>

                <article class="lp-card">
                    <span class="lp-card__icon lp-card__icon--3" aria-hidden="true">❓</span>
                    <h3>Kuis</h3>
                    <p>Siswa dapat mengerjakan kuis setelah mempelajari materi dan mendapatkan nilai.</p>
                </article>

                <article class="lp-card">
                    <span class="lp-card__icon lp-card__icon--4" aria-hidden="true">📈</span>
                    <h3>Progress Belajar</h3>
                    <p>Siswa dapat melihat materi yang sudah dipelajari, materi yang belum dipelajari, dan persentase perkembangan belajar.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ CARA BELAJAR ============ --}}
    <section class="lp-section" id="cara-belajar">
        <div class="lp-container">
            <div class="lp-section__head">
                <span class="lp-pill lp-pill--soft">Langkah</span>
                <h2>Cara Belajar</h2>
                <p class="lp-section__sub">Empat langkah sederhana untuk mulai belajar di platform.</p>
            </div>

            <ol class="lp-grid lp-grid--4 lp-steps">
                <li class="lp-step">
                    <span class="lp-step__num" aria-hidden="true">1</span>
                    <span class="lp-step__icon" aria-hidden="true">🔑</span>
                    <h3>Login</h3>
                    <p>Siswa masuk ke dalam platform.</p>
                </li>
                <li class="lp-step">
                    <span class="lp-step__num" aria-hidden="true">2</span>
                    <span class="lp-step__icon" aria-hidden="true">📚</span>
                    <h3>Pilih Materi</h3>
                    <p>Siswa memilih materi IPA yang tersedia.</p>
                </li>
                <li class="lp-step">
                    <span class="lp-step__num" aria-hidden="true">3</span>
                    <span class="lp-step__icon" aria-hidden="true">📖</span>
                    <h3>Pelajari Modul</h3>
                    <p>Siswa mempelajari modul singkat secara bertahap.</p>
                </li>
                <li class="lp-step">
                    <span class="lp-step__num" aria-hidden="true">4</span>
                    <span class="lp-step__icon" aria-hidden="true">✍️</span>
                    <h3>Kerjakan Kuis</h3>
                    <p>Siswa mengerjakan kuis dan melihat hasilnya.</p>
                </li>
            </ol>
        </div>
    </section>

    {{-- ============ MICRO-LEARNING ============ --}}
    <section class="lp-section lp-section--alt" id="micro-learning">
        <div class="lp-container lp-ml">
            <div class="lp-section__head">
                <span class="lp-pill lp-pill--soft">Micro-learning</span>
                <h2>Belajar Sedikit Demi Sedikit, Lebih Mudah Dipahami</h2>
                <p class="lp-section__sub">Materi pembelajaran dibagi menjadi beberapa modul singkat sehingga siswa dapat mempelajari materi secara bertahap.</p>
            </div>

            <ol class="lp-flow">
                <li class="lp-flow__item lp-flow__item--start">
                    <span aria-hidden="true">📚</span> Materi
                </li>
                <li class="lp-flow__item"><span aria-hidden="true">📗</span> Modul 1</li>
                <li class="lp-flow__item"><span aria-hidden="true">📘</span> Modul 2</li>
                <li class="lp-flow__item"><span aria-hidden="true">📙</span> Modul 3</li>
                <li class="lp-flow__item"><span aria-hidden="true">❓</span> Kuis</li>
                <li class="lp-flow__item lp-flow__item--end"><span aria-hidden="true">📈</span> Progress</li>
            </ol>
        </div>
    </section>

    {{-- ============ GURU ============ --}}
    <section class="lp-section" id="guru">
        <div class="lp-container lp-split">
            <div class="lp-split__text">
                <span class="lp-pill lp-pill--soft">Untuk Guru</span>
                <h2>Guru Tetap Dapat Memantau Pembelajaran</h2>
                <p>Guru memiliki dashboard untuk mengelola pembelajaran dan memantau perkembangan siswa.</p>

                <ul class="lp-checklist">
                    <li>Mengelola materi pembelajaran.</li>
                    <li>Mengelola soal kuis/evaluasi.</li>
                    <li>Memantau progress belajar siswa.</li>
                </ul>

                <a class="btn btn-primary" href="{{ route('login') }}">Login sebagai Guru</a>
            </div>

            <div class="lp-split__visual" aria-hidden="true">
                <div class="lp-panel">
                    <div class="lp-panel__row"><span>📖 Materi</span><span class="lp-chip">4</span></div>
                    <div class="lp-panel__row"><span>❓ Kuis</span><span class="lp-chip">4</span></div>
                    <div class="lp-panel__row"><span>📊 Progress siswa</span><span class="lp-chip lp-chip--green">aktif</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="lp-cta" id="mulai">
        <div class="lp-container lp-cta__inner">
            <span class="lp-cta__emoji" aria-hidden="true">🚀</span>
            <h2>Siap Mulai Belajar IPA?</h2>
            <p>Pelajari materi IPA kelas III secara bertahap melalui pembelajaran micro-learning.</p>
            <a class="btn btn-fun btn-lg" href="{{ route('login') }}">Mulai Belajar</a>
        </div>
    </section>

</main>

<footer class="lp-footer">
    <div class="lp-container lp-footer__grid">
        <div class="lp-footer__brand">
            <a class="lp-brand" href="{{ url('/') }}">
                <span class="lp-brand__logo" aria-hidden="true">🔬</span>
                <span class="lp-brand__text">Platform Pembelajaran IPA</span>
            </a>
            <p>Platform pembelajaran IPA interaktif berbasis web dengan pendekatan micro-learning untuk siswa sekolah dasar.</p>
        </div>

        <nav class="lp-footer__links" aria-label="Menu footer">
            <a href="#beranda">Beranda</a>
            <a href="#fitur">Fitur</a>
            <a href="#tentang">Tentang</a>
            <a href="{{ route('login') }}">Login</a>
        </nav>
    </div>

    <div class="lp-footer__copy">
        <div class="lp-container">© 2026 Platform Pembelajaran IPA</div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
