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
            return redirect('/admin');
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

        // Try login with email, nim, or nip
        $credentials = ['password' => $password];

        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginField;
        } elseif (preg_match('/^\d{10,}$/', $loginField)) {
            // Could be NIM or NIP based on length
            // Try NIM first (shorter), then NIP
            if (strlen($loginField) <= 15) {
                $credentials['nim'] = $loginField;
            } else {
                $credentials['nip'] = $loginField;
            }
        } else {
            $credentials['email'] = $loginField;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                ])->withInput($request->only('email'));
            }

            return redirect()->intended('/admin');
        }

        // If NIM/NIP attempt failed, try the other field
        if (isset($credentials['nim'])) {
            unset($credentials['nim']);
            $credentials['nip'] = $loginField;
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/admin');
            }
        } elseif (isset($credentials['nip'])) {
            unset($credentials['nip']);
            $credentials['nim'] = $loginField;
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/admin');
            }
        }

        return back()->withErrors([
            'email' => 'NIM/NIP/Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
}
