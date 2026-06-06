<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Get redirect path based on user role.
     */
    protected function redirectPathForUser($user): string
    {
        return match ($user->role) {
            'mahasiswa' => '/mahasiswa',
            'koordinator' => '/koordinator',
            'wadir1' => '/wadir',
            'dosen', 'kps', 'kajur' => '/dosen',
            'admin' => '/admin',
            default => '/mahasiswa',
        };
    }

    /**
     * Show the custom login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->redirectPathForUser(Auth::user()));
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
        } elseif (preg_match('/^\d+$/', $loginField)) {
            // Try NIM first (longer inputs or based on length check)
            $credentials['nim'] = $loginField;
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

            return redirect($this->redirectPathForUser($user));
        }

        // If NIM/NIP attempt failed, try the other field
        if (isset($credentials['nim'])) {
            unset($credentials['nim']);
            $credentials['nip'] = $loginField;
            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user();
                if (!$user->is_active) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                    ])->withInput($request->only('email'));
                }
                $request->session()->regenerate();
                return redirect($this->redirectPathForUser($user));
            }
        } elseif (isset($credentials['nip'])) {
            unset($credentials['nip']);
            $credentials['nim'] = $loginField;
            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user();
                if (!$user->is_active) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                    ])->withInput($request->only('email'));
                }
                $request->session()->regenerate();
                return redirect($this->redirectPathForUser($user));
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
