<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.reports.index');
        }

        $rememberedEmail = $request->cookie('remember_admin_email');

        return view('admin.login', compact('rememberedEmail'));
    }

    /**
     * Proses autentikasi login admin
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($remember) {
                // Simpan email ke cookie selama 30 hari (43200 menit)
                cookie()->queue('remember_admin_email', $credentials['email'], 43200);
            } else {
                cookie()->queue(cookie()->forget('remember_admin_email'));
            }

            return redirect()->intended(route('admin.reports.index'));
        }

        // Pesan error umum sesuai PRD Section 6.6
        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Proses logout admin
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

