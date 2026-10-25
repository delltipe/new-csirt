<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoAuthController extends Controller
{
    /**
     * Show the safe preview demo entry page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login requests in the public preview.
     * Rejects real credentials with 403 to prevent password entry.
     */
    public function login(Request $request)
    {
        abort(403, 'Autentikasi akun nyata dinonaktifkan pada versi pratinjau publik. Silakan lanjutkan sebagai pelapor demo.');
    }

    /**
     * Show the safe preview registration page.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration requests in the public preview.
     * Strictly rejects account creation to prevent collecting user credentials.
     */
    public function register(Request $request)
    {
        abort(403, 'Pendaftaran akun dinonaktifkan pada versi pratinjau publik. Silakan lanjutkan sebagai pelapor demo.');
    }

    /**
     * Handle logout in the public preview.
     */
    public function logout(Request $request)
    {
        abort(403, 'Autentikasi dinonaktifkan pada versi pratinjau publik.');
    }
}
