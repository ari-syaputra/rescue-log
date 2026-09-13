<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posko;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Opsi A: Login Menggunakan Access Key Kode Undangan (Role Lapangan)
        if ($request->filled('kode_undangan')) {
            $request->validate([
                'kode_undangan' => ['required', 'string'],
            ]);

            $posko = Posko::where('kode_undangan', strtoupper(trim($request->kode_undangan)))->first();

            if (!$posko) {
                return back()->withErrors([
                    'kode_undangan' => 'Access Key posko tidak ditemukan atau tidak valid.',
                ])->withInput();
            }

            // Cari user petugas lapangan yang terhubung ke posko ini
            $user = User::where('posko_id', $posko->id)
                ->where('role', 'lapangan')
                ->first();

            // Buat user otomatis jika belum ada user terikat pada posko tersebut
            if (!$user) {
                $user = User::create([
                    'name'     => 'Petugas ' . $posko->nama_posko,
                    'email'    => 'petugas.' . strtolower(str_replace('-', '', $posko->kode_undangan)) . '@rescuelog.id',
                    'password' => bcrypt('password123'),
                    'role'     => 'lapangan',
                    'posko_id' => $posko->id,
                    'bpbd_id'  => $posko->bpbd_id,
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
            if (in_array($user->role, ['admin', 'bpbd', 'bpbd_kabkota'])) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil login! Selamat datang di Dashboard Admin BPBD.');
            } 
            elseif (in_array($user->role, ['komando', 'koordinator_komando', 'posko_komando'])) {
                return redirect()->route('komando.dashboard')
                    ->with('success', 'Berhasil login! Selamat datang di Posko Komando Utama.');
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}