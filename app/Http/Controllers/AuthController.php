<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->user()) {
            return redirect($request->user()->dashboardRoute());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'login' => 'email atau username',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $credentials['login'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['login' => 'Data login tidak valid.'])
                ->onlyInput('login');
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended($user->dashboardRoute());
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegister(Request $request)
    {
        if ($request->user()) {
            return redirect($request->user()->dashboardRoute());
        }

        return view('auth.register');
    }

    public function showStudentRegister(Request $request)
    {
        return $this->showRegisterForm($request, 'auth.register_student');
    }

    public function showTeacherRegister(Request $request)
    {
        return $this->showRegisterForm($request, 'auth.register_teacher');
    }

    public function registerStudent(Request $request)
    {
        return $this->createAccount($request, 'siswa');
    }

    public function registerTeacher(Request $request)
    {
        return $this->createAccount($request, 'guru');
    }

    private function showRegisterForm(Request $request, string $view)
    {
        if ($request->user()) {
            return redirect($request->user()->dashboardRoute());
        }

        return view($view);
    }

    private function createAccount(Request $request, string $role)
    {
        if ($request->user()) {
            return redirect($request->user()->dashboardRoute());
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 30 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan. Silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ], [
            'name' => 'nama lengkap',
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended($user->dashboardRoute());
    }
}
