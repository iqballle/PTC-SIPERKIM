<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleAuthController extends Controller
{
    /* ====================== DEVELOPER ====================== */

    // GET /developer/login
    public function showDeveloperLogin()
    {
        return view('auth.developer-login');
    }

    // POST /developer/login
    public function developerLogin(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cred, true)) {
            $request->session()->regenerate();

            if (Auth::user()->role !== 'developer') {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Akun ini bukan Developer.'])
                    ->onlyInput('email');
            }

            return redirect()->route('developer.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    // GET /developer/register
    public function showDeveloperRegister()
    {
        return view('auth.developer-register');
    }

    // POST /developer/register
    public function registerDeveloper(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'developer',
        ]);

        return redirect()->route('developer.login')
            ->with('status', 'Akun Developer berhasil dibuat. Silakan login.');
    }


    /* ======================== DINAS ======================== */

    // GET /dinas/login
    public function showDinasLogin()
    {
        return view('auth.dinas-login');
    }

    // POST /dinas/login
    public function dinasLogin(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cred, true)) {
            $request->session()->regenerate();

            if (Auth::user()->role !== 'dinas') {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'Akun ini bukan Dinas.'])
                    ->onlyInput('email');
            }

            return redirect()->route('dinas.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    // GET /dinas/register
    public function showDinasRegister()
    {
        return view('auth.dinas-register');
    }

    // POST /dinas/register
    public function registerDinas(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'min:6', 'confirmed'],
            // Jika mau batasi pendaftaran dinas, tambahkan validasi access_code di sini
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'dinas',
        ]);

        return redirect()->route('dinas.login')
            ->with('status', 'Akun Dinas berhasil dibuat. Silakan login.');
    }


    /* ===================== COMMON ACTIONS ===================== */

    // POST /logout (bisa dipakai kedua peran)
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('landing');
    }

}