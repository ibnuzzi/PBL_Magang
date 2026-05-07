@extends('layouts.app')

@section('title', 'Lupa Password — SiMagang JTI Polinema')

@push('styles')
<style>
    .login-hero-overlay {
        background: linear-gradient(135deg, rgba(0,43,86,0.92) 0%, rgba(0,59,122,0.80) 60%, rgba(0,43,86,0.65) 100%);
    }
    .login-page {
        display: flex;
        min-height: calc(100vh - 57px);
    }
    .login-left {
        flex: 1.1;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 3rem 3.5rem;
    }
    .login-right {
        flex: 0.9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #F1F5F9;
    }
    .login-card {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 25px 60px rgba(0,43,86,0.10), 0 4px 16px rgba(0,0,0,0.06);
        padding: 2.5rem 2.25rem 2rem;
        width: 100%;
        max-width: 420px;
    }
    .login-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.6rem;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        color: #0F172A;
        background: #F8FAFC;
    }
    .login-input:focus {
        border-color: #003B7A;
        box-shadow: 0 0 0 3px rgba(0,59,122,0.08);
        background: #fff;
    }
    .login-input::placeholder { color: #94A3B8; }
    .login-btn {
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 0.6rem;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: opacity 0.2s, transform 0.15s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .login-btn:hover { opacity: 0.92; transform: translateY(-1px); }
    .login-btn:active { transform: translateY(0); }
    .btn-primary-login { background: #F5A623; color: #7A4500; }
    .btn-outline-login {
        background: transparent;
        border: 1.5px solid #E2E8F0;
        color: #374151;
        text-decoration: none;
    }
    .btn-outline-login:hover { border-color: #003B7A; color: #003B7A; }
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .feature-icon {
        width: 36px;
        height: 36px;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(245,166,35,0.20);
    }
    .login-footer-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #E2E8F0;
    }
    .login-footer-links a {
        font-size: 0.7rem;
        color: #94A3B8;
        text-decoration: none;
        transition: color 0.2s;
    }
    .login-footer-links a:hover { color: #003B7A; }
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.75rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .alert-success {
        background: rgba(16,185,129,0.08);
        border: 1px solid rgba(16,185,129,0.25);
        color: #065F46;
        padding: 0.75rem 1rem;
        border-radius: 0.6rem;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .alert-error {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.25);
        color: #991B1B;
        padding: 0.75rem 1rem;
        border-radius: 0.6rem;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    @media (max-width: 900px) {
        .login-page { flex-direction: column; }
        .login-left { min-height: 340px; padding: 2rem 1.5rem; }
        .login-right { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')

{{-- ==================== NAVBAR ==================== --}}
<header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color:#003B7A;">
                <svg viewBox="0 0 40 40" fill="none" class="w-7 h-7">
                    <rect x="6" y="10" width="28" height="20" rx="2" fill="white" fill-opacity="0.2"/>
                    <rect x="9" y="14" width="10" height="12" rx="1" fill="white" fill-opacity="0.8"/>
                    <rect x="21" y="14" width="10" height="5" rx="1" fill="white"/>
                    <rect x="21" y="21" width="10" height="5" rx="1" fill="#F5A623"/>
                </svg>
            </div>
            <div>
                <div class="font-extrabold text-base leading-tight pjs" style="color:#003B7A;">SiMagang JTI</div>
                <div class="text-xs text-gray-500 leading-tight">Polinema · Teknologi Informasi</div>
            </div>
        </div>
        <nav class="hidden md:flex items-center gap-8">
            <a href="/" class="nav-link text-sm">Beranda</a>
            <a href="/#jenis-magang" class="nav-link text-sm">Jenis Magang</a>
            <a href="/#alur-pendaftaran" class="nav-link text-sm">Alur Pendaftaran</a>
            <a href="/#fitur" class="nav-link text-sm">Fitur</a>
            <a href="/#footer" class="nav-link text-sm">Kontak</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="px-5 py-2 text-sm font-semibold rounded-lg border-2 transition-all pjs"
               style="border-color:#003B7A; color:#003B7A; background-color:rgba(0,59,122,0.06);">
                Masuk
            </a>
        </div>
    </div>
</header>

{{-- ==================== FORGOT PASSWORD PAGE ==================== --}}
<div class="login-page">

    {{-- LEFT: Hero Section --}}
    <div class="login-left">
        <div class="absolute inset-0">
            <img src="https://sfile.ii.inc/image_search/e8b60efd86f6.jpg"
                 alt="Kampus Polinema" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 login-hero-overlay"></div>
        </div>

        <div class="relative z-10 max-w-lg">
            {{-- Brand --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.15);">
                    <svg viewBox="0 0 40 40" fill="none" class="w-7 h-7">
                        <rect x="6" y="10" width="28" height="20" rx="2" fill="white" fill-opacity="0.2"/>
                        <rect x="9" y="14" width="10" height="12" rx="1" fill="white" fill-opacity="0.8"/>
                        <rect x="21" y="14" width="10" height="5" rx="1" fill="white"/>
                        <rect x="21" y="21" width="10" height="5" rx="1" fill="#F5A623"/>
                    </svg>
                </div>
                <div>
                    <div class="font-extrabold text-base text-white pjs">SIMagang JTI</div>
                    <div class="text-xs text-blue-200">Politeknik Negeri Malang</div>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="text-4xl font-extrabold text-white leading-tight mb-3 pjs">
                Lupa Password?<br>
                <span style="color:#F5A623;">Kami Bantu Reset</span>
            </h1>
            <p class="text-blue-100 text-sm leading-relaxed mb-8 max-w-md" style="opacity:0.85;">
                Jangan khawatir, hal ini sering terjadi. Masukkan email atau NIM yang terdaftar, dan kami akan mengirimkan instruksi untuk mengatur ulang password Anda.
            </p>

            {{-- Steps --}}
            <div class="step-item">
                <div class="step-number" style="background:rgba(245,166,35,0.20); color:#F5A623;">1</div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Masukkan Email atau NIM</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Isi kolom dengan email atau NIM yang terdaftar di sistem.</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number" style="background:rgba(245,166,35,0.20); color:#F5A623;">2</div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Cek Email Anda</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Kami akan mengirimkan link reset password ke email yang terdaftar.</div>
                </div>
            </div>
            <div class="step-item">
                <div class="step-number" style="background:rgba(245,166,35,0.20); color:#F5A623;">3</div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Buat Password Baru</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Klik link di email dan buat password baru untuk akun Anda.</div>
                </div>
            </div>
        </div>

        {{-- Quote --}}
        <div class="relative z-10 mt-auto pt-8">
            <div class="border-t border-white border-opacity-15 pt-6">
                <p class="text-blue-100 text-xs italic leading-relaxed" style="opacity:0.7;">
                    "Keamanan akun adalah prioritas kami —<br>
                    pastikan selalu gunakan password yang kuat."
                </p>
                <p class="text-blue-200 text-xs mt-2 pjs font-semibold" style="opacity:0.6;">
                    Tim IT SiMagang JTI Polinema
                </p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Forgot Password Form --}}
    <div class="login-right">
        <div class="login-card">
            {{-- Badge --}}
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold pjs"
                      style="background:rgba(0,59,122,0.08); color:#003B7A;">
                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#F5A623;"></span>
                    Reset Password
                </span>
            </div>

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:rgba(0,59,122,0.08);">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="#003B7A" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>

            {{-- Heading --}}
            <h2 class="text-2xl font-extrabold text-center mb-1 pjs" style="color:#003B7A;">
                Atur Ulang Password
            </h2>
            <p class="text-gray-400 text-xs text-center mb-6 leading-relaxed max-w-xs mx-auto">
                Masukkan email atau NIM yang terdaftar di sistem. Kami akan mengirimkan link untuk mengatur ulang password Anda.
            </p>

            {{-- Status Messages --}}
            @if (session('status'))
                <div class="alert-success">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.request') }}" id="forgotForm">
                @csrf

                {{-- Email / NIM --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 pjs">Email / NIM</label>
                    <input type="text" name="email"
                           class="login-input" placeholder="Masukkan email atau NIM Anda"
                           value="{{ old('email') }}" required autofocus>
                    <p class="text-gray-400 text-xs mt-1">
                        Contoh: nama@students.polinema.ac.id atau 2341720203
                    </p>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-btn btn-primary-login mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Kirim Link Reset Password
                </button>
            </form>

            {{-- Divider --}}
            <p class="text-center text-xs text-gray-400 mb-3">Sudah ingat password?</p>

            {{-- Back to Login --}}
            <a href="{{ route('login') }}" class="login-btn btn-outline-login">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Kembali ke Halaman Login
            </a>

            {{-- Info Box --}}
            <div class="mt-5 p-3 rounded-xl" style="background:rgba(0,59,122,0.04); border:1px solid rgba(0,59,122,0.08);">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#003B7A" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold pjs" style="color:#003B7A;">Tidak menerima email?</p>
                        <p class="text-xs text-gray-400 leading-relaxed mt-0.5">
                            Periksa folder spam/junk Anda. Jika masih tidak menerima email, hubungi admin di
                            <a href="mailto:magang@jti.polinema.ac.id" style="color:#F5A623; text-decoration:none; font-weight:600;">magang@jti.polinema.ac.id</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Bottom Status & Links --}}
            <div class="flex items-center justify-center gap-4 mt-4 pt-3" style="border-top:1px solid #F1F5F9;">
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:#10B981;"></span>
                    <span class="text-xs text-gray-400">v2.4.1</span>
                </div>
                <span class="text-gray-200">·</span>
                <span class="text-xs text-gray-400">Sistem Online</span>
                <span class="text-gray-200">·</span>
                <span class="text-xs text-gray-400">JTI Polinema © {{ date('Y') }}</span>
            </div>
            <div class="login-footer-links">
                <a href="/">← Kembali ke Beranda</a>
                <a href="#">Informasi & FAQ</a>
                <a href="#">Hubungi Admin</a>
            </div>
        </div>
    </div>
</div>

@endsection
