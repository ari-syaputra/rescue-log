<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posko;
use App\Models\User;

class AuthController extends Controller
{
    // 1. Menampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Memproses Autentikasi Login (Email/Password & Kode Undangan Posko)
    public function login(Request $request)
    {
        // Opsi A: Login Menggunakan Kode Undangan (Role Lapangan)
        if ($request->filled('kode_undangan')) {
            $request->validate([
                'kode_undangan' => ['required', 'string'],
            ]);

            $posko = Posko::where('kode_undangan', strtoupper(trim($request->kode_undangan)))->first();

            if (!$posko) {
                return back()->withErrors([
                    'kode_undangan' => 'Kode undangan posko tidak ditemukan atau tidak valid.',
                ])->onlyInput('kode_undangan');
            }

            // Cari user petugas lapangan yang terhubung ke posko ini
            $user = User::where('posko_id', $posko->id)
                ->where('role', 'lapangan')
                ->first();

            // Opsional: Buat user otomatis jika belum ada user terikat pada posko tersebut
            if (!$user) {
                $user = User::create([
                    'name'     => 'Petugas ' . $posko->nama_posko,
                    'email'    => 'petugas.' . strtolower($posko->kode_undangan) . '@posko.local',
                    'password' => bcrypt('password123'),
                    'role'     => 'lapangan',
                    'posko_id' => $posko->id,
                ]);
            }

            // Login-kan user secara manual
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('lapangan.dashboard')
                ->with('success', "Berhasil masuk ke {$posko->nama_posko}!");
        }

        // Opsi B: Login Standard (Email & Password)
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengalihan berdasarkan Role
            if ($user->role === 'admin' || $user->role === 'bpbd') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil login! Selamat datang di Dashboard Admin BPBD.');
            } 
            elseif (in_array($user->role, ['komando', 'koordinator_komando', 'posko_komando'])) {
                return redirect()->route('komando.dashboard')
                    ->with('success', 'Berhasil login! Selamat datang di Posko Komando.');
            } 
            elseif ($user->role === 'lapangan') {
                return redirect()->route('lapangan.dashboard')
                    ->with('success', 'Berhasil login! Selamat datang di Posko Lapangan.');
            }

            return redirect('/login')->with('error', 'Role akun Anda tidak memiliki akses dashboard.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}