<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait ManagesUserAccounts
{
    protected function validasiAkun(Request $request, User $user): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:30', 'unique:users,username,' . $user->id . ',id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama terlalu panjang.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'username.max' => 'Username maksimal 30 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);
    }

    protected function perbaruiAkun(User $user, array $data): void
    {
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];

        // Password bersifat opsional: kosong = pertahankan password lama.
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Field role tidak disentuh, sehingga peran user tetap seperti semula.
        $user->save();
    }
}
