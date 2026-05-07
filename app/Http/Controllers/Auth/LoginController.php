<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the custom login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = $request->input('email');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $credentials = ['password' => $password];

        // Detect login type (email / nim / nip)
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginField;
        } elseif (preg_match('/^\d{10,}$/', $loginField)) {
            if (strlen($loginField) <= 15) {
                $credentials['nim'] = $loginField;
            } else {
                $credentials['nip'] = $loginField;
            }
        } else {
            $credentials['email'] = $loginField;
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // cek aktif
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                ])->withInput($request->only('email'));
            }

            // redirect sesuai role
            return $this->redirectBasedOnRole($user);
        }

        // fallback: coba nim <-> nip
        if (isset($credentials['nim'])) {
            unset($credentials['nim']);
            $credentials['nip'] = $loginField;
        } elseif (isset($credentials['nip'])) {
            unset($credentials['nip']);
            $credentials['nim'] = $loginField;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        // gagal login
        return back()->withErrors([
            'email' => 'NIM/NIP/Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Redirect berdasarkan role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin');
        } elseif ($user->role === 'mahasiswa') {
            return redirect('/mahasiswa');
        } elseif ($user->role === 'dosen') {
            return redirect('/dosen');
        } else {
            return redirect('/');
        }
    }

    /**
     * Show forgot password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
}