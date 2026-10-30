@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Guru</h1>
            <p>Perbarui data guru. Password baru bersifat opsional — kosongkan untuk mempertahankan password lama.</p>
        </div>
        <a class="btn btn-outline" href="{{ route('admin.guru.index') }}">← Kembali</a>
    </div>

    <div class="card" style="max-width: 720px;">
        <form action="{{ route('admin.guru.update', $guru) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Guru</label>
                <input class="form-input @error('name') is-error @enderror"
                       type="text" id="name" name="name"
                       value="{{ old('name', $guru->name) }}"
                       placeholder="Nama lengkap guru" required>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-input @error('username') is-error @enderror"
                           type="text" id="username" name="username"
                           value="{{ old('username', $guru->username) }}"
                           placeholder="Username unik" required>
                    @error('username')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input @error('email') is-error @enderror"
                           type="email" id="email" name="email"
                           value="{{ old('email', $guru->email) }}"
                           placeholder="Email aktif" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="password">Password Baru <span class="text-secondary">(opsional)</span></label>
                    <input class="form-input @error('password') is-error @enderror"
                           type="password" id="password" name="password"
                           placeholder="Kosongkan jika tidak diganti" autocomplete="new-password">
                    <span class="form-hint">Kosongkan untuk mempertahankan password lama.</span>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Ulangi Password Baru</label>
                    <input class="form-input @error('password_confirmation') is-error @enderror"
                           type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Ulangi password baru" autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <p class="form-hint" style="margin-bottom: 16px;">Peran guru tetap <strong>guru</strong> dan tidak dapat diubah melalui form ini.</p>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                <a class="btn btn-outline" href="{{ route('admin.guru.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
