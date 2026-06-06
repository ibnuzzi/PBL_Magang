@extends('layouts.app')

@section('title', 'Masuk — SiMagang JTI Polinema')

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

{{-- ==================== LOGIN PAGE ==================== --}}
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
                Sistem Informasi<br>Magang Terpadu
            </h1>
            <p class="text-blue-100 text-sm leading-relaxed mb-8 max-w-md" style="opacity:0.85;">
                Kelola seluruh proses magang Jurusan Teknologi Informasi — dari pendaftaran hingga penilaian — dalam satu platform terintegrasi.
            </p>

            {{-- Features --}}
            <div class="feature-item">
                <div class="feature-icon">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Pendaftaran Digital Tanpa Antri</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Ajukan permohonan magang kapan saja dan dari mana saja secara online.</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Monitoring Progress Real-time</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Pantau status pendaftaran, logbook, dan penilaian secara langsung.</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-white font-bold text-sm pjs">Kolaborasi Multi-role Terintegrasi</div>
                    <div class="text-blue-200 text-xs leading-relaxed" style="opacity:0.75;">Mahasiswa, Dosen, Koordinator, dan Admin dalam satu ekosistem terhubung.</div>
                </div>
            </div>
        </div>

        {{-- Quote --}}
        <div class="relative z-10 mt-auto pt-8">
            <div class="border-t border-white border-opacity-15 pt-6">
                <p class="text-blue-100 text-xs italic leading-relaxed" style="opacity:0.7;">
                    "Magang bukan sekadar kewajiban — ini adalah<br>
                    jembatan antara ilmu dan karir nyata."
                </p>
                <p class="text-blue-200 text-xs mt-2 pjs font-semibold" style="opacity:0.6;">
                    Jurusan Teknologi Informasi, Polinema
                </p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Login Form --}}
    <div class="login-right">
        <div class="login-card">
            {{-- Badge --}}
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold pjs"
                      style="background:rgba(0,59,122,0.08); color:#003B7A;">
                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#F5A623;"></span>
                    Portal Magang JTI
                </span>
            </div>

            {{-- Heading --}}
            <h2 class="text-2xl font-extrabold text-center mb-1 pjs" style="color:#003B7A;">
                Selamat Datang Kembali
            </h2>
            <p class="text-gray-400 text-xs text-center mb-3 leading-relaxed">
                Masuk ke akun Anda untuk mengelola kegiatan magang.
            </p>
            {{-- Logo JTI --}}
            <div class="flex justify-center mb-5">
                <div style="display:inline-flex; align-items:center; justify-content:center; width:88px; height:88px; border-radius:1rem; background:#F8FAFC; box-shadow:0 2px 12px rgba(0,43,86,0.10), 0 1px 3px rgba(0,0,0,0.06); border:1px solid #E2E8F0;">
                    <img src="{{ asset('images/logo-jti.png') }}" alt="Logo JTI Polinema" style="width:60px; height:auto; object-fit:contain;">
                </div>
            </div>



            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                {{-- NIM / Email --}}
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1 pjs">NIM / NIP / Email</label>
                    <input type="text" name="email" id="loginEmail"
                           class="login-input" placeholder="Masukkan NIM, NIP, atau Email"
                           value="{{ old('email') }}" required>
                    <p class="text-gray-400 text-xs mt-1" id="loginHint">
                        Contoh: NIM (224176...), NIP (199103...), atau Email
                    </p>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-gray-600 pjs">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold pjs" style="color:#F5A623; text-decoration:none;">Lupa Password?</a>
                    </div>
                    <input type="password" name="password"
                           class="login-input" placeholder="Masukkan password Anda" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-gray-300" style="accent-color:#003B7A;">
                    <label for="remember" class="text-xs text-gray-500">Ingat saya di perangkat ini</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-btn btn-primary-login mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Masuk ke Sistem
                </button>
            </form>



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


