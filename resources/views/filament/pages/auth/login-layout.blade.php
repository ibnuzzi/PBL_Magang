@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

<style>
    /* Override Filament's default simple layout */
    .fi-simple-layout { display: none !important; }
    
    .login-hero-overlay {
        background: linear-gradient(135deg, rgba(0,43,86,0.92) 0%, rgba(0,59,122,0.80) 60%, rgba(0,43,86,0.65) 100%);
    }
    .login-page {
        display: flex;
        min-height: 100vh;
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
    .pjs { font-family: 'Plus Jakarta Sans', sans-serif; }
    .nav-link-custom {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: color 0.2s;
        font-size: 0.875rem;
    }
    .nav-link-custom:hover { color: #003B7A; }

    /* Override Filament form styles inside our card */
    .login-card .fi-fo-field-wrp { margin-bottom: 0.75rem; }
    .login-card .fi-fo-field-wrp label { 
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        color: #4B5563;
    }
    .login-card .fi-input { 
        background: #F8FAFC !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 0.6rem !important;
    }
    .login-card .fi-input:focus-within {
        border-color: #003B7A !important;
        box-shadow: 0 0 0 3px rgba(0,59,122,0.08) !important;
        background: #fff !important;
    }
    .login-card .fi-btn-primary {
        background: #F5A623 !important;
        color: #7A4500 !important;
        border: none !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        border-radius: 0.6rem !important;
        transition: opacity 0.2s, transform 0.15s;
    }
    .login-card .fi-btn-primary:hover { 
        opacity: 0.92; 
        transform: translateY(-1px);
        background: #e89b1d !important;
    }
    .login-card .fi-checkbox-label {
        font-size: 0.75rem;
        color: #6B7280;
    }

    @media (max-width: 900px) {
        .login-page { flex-direction: column; }
        .login-left { min-height: 340px; padding: 2rem 1.5rem; }
        .login-right { padding: 1.5rem; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

{{-- ==================== NAVBAR ==================== --}}
<header style="background:#fff; border-bottom:1px solid #F3F4F6; box-shadow:0 1px 3px rgba(0,0,0,0.05); position:sticky; top:0; z-index:50;">
    <div style="max-width:80rem; margin:0 auto; padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
        <a href="/" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none;">
            <div style="width:2.5rem; height:2.5rem; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; background-color:#003B7A;">
                <img src="{{ asset('images/logo-jti.png') }}" alt="Logo JTI" style="width:1.75rem; height:1.75rem; object-fit:contain;">
            </div>
            <div>
                <div class="pjs" style="font-weight:800; font-size:1rem; line-height:1.2; color:#003B7A;">SiMagang JTI</div>
                <div style="font-size:0.75rem; color:#6B7280; line-height:1.2;">Polinema · Teknologi Informasi</div>
            </div>
        </a>
        <nav style="display:flex; align-items:center; gap:2rem;" class="hidden md:flex">
            <a href="/" class="nav-link-custom">Beranda</a>
            <a href="/#jenis-magang" class="nav-link-custom">Jenis Magang</a>
            <a href="/#alur-pendaftaran" class="nav-link-custom">Alur Pendaftaran</a>
            <a href="/#fitur" class="nav-link-custom">Fitur</a>
            <a href="/#footer" class="nav-link-custom">Kontak</a>
        </nav>
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <a href="{{ filament()->getLoginUrl() }}"
               class="pjs"
               style="padding:0.5rem 1.25rem; font-size:0.875rem; font-weight:600; border-radius:0.5rem; border:2px solid #003B7A; color:#003B7A; background:rgba(0,59,122,0.06); text-decoration:none; transition:all 0.2s;">
                Masuk
            </a>
        </div>
    </div>
</header>

{{-- ==================== LOGIN PAGE ==================== --}}
<div class="login-page">

    {{-- LEFT: Hero Section --}}
    <div class="login-left">
        <div style="position:absolute; inset:0;">
            <img src="https://sfile.ii.inc/image_search/e8b60efd86f6.jpg"
                 alt="Kampus Polinema" style="width:100%; height:100%; object-fit:cover; object-position:center;">
            <div class="login-hero-overlay" style="position:absolute; inset:0;"></div>
        </div>

        <div style="position:relative; z-index:10; max-width:32rem;">
            {{-- Brand --}}
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:2rem;">
                <div style="width:2.75rem; height:2.75rem; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; background:rgba(255,255,255,0.15);">
                    <img src="{{ asset('images/logo-jti.png') }}" alt="Logo JTI" style="width:1.75rem; height:1.75rem; object-fit:contain;">
                </div>
                <div>
                    <div class="pjs" style="font-weight:800; font-size:1rem; color:#fff;">SIMagang JTI</div>
                    <div style="font-size:0.75rem; color:#93C5FD;">Politeknik Negeri Malang</div>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="pjs" style="font-size:2.5rem; font-weight:800; color:#fff; line-height:1.15; margin:0 0 0.75rem 0;">
                Sistem Informasi<br>Magang Terpadu
            </h1>
            <p style="color:#BFDBFE; font-size:0.875rem; line-height:1.7; margin-bottom:2rem; max-width:28rem; opacity:0.85;">
                Kelola seluruh proses magang Jurusan Teknologi Informasi — dari pendaftaran hingga penilaian — dalam satu platform terintegrasi.
            </p>

            {{-- Features --}}
            <div class="feature-item">
                <div class="feature-icon">
                    <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="pjs" style="color:#fff; font-weight:700; font-size:0.875rem;">Pendaftaran Digital Tanpa Antri</div>
                    <div style="color:#93C5FD; font-size:0.75rem; line-height:1.5; opacity:0.75;">Ajukan permohonan magang kapan saja dan dari mana saja secara online.</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="pjs" style="color:#fff; font-weight:700; font-size:0.875rem;">Monitoring Progress Real-time</div>
                    <div style="color:#93C5FD; font-size:0.75rem; line-height:1.5; opacity:0.75;">Pantau status pendaftaran, logbook, dan penilaian secara langsung.</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg style="width:1rem; height:1rem;" fill="none" viewBox="0 0 24 24" stroke="#F5A623" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="pjs" style="color:#fff; font-weight:700; font-size:0.875rem;">Kolaborasi Multi-role Terintegrasi</div>
                    <div style="color:#93C5FD; font-size:0.75rem; line-height:1.5; opacity:0.75;">Mahasiswa, Dosen, Koordinator, dan Admin dalam satu ekosistem terhubung.</div>
                </div>
            </div>
        </div>

        {{-- Quote --}}
        <div style="position:relative; z-index:10; margin-top:auto; padding-top:2rem;">
            <div style="border-top:1px solid rgba(255,255,255,0.15); padding-top:1.5rem;">
                <p style="color:#BFDBFE; font-size:0.75rem; font-style:italic; line-height:1.6; opacity:0.7;">
                    "Magang bukan sekadar kewajiban — ini adalah<br>
                    jembatan antara ilmu dan karir nyata."
                </p>
                <p class="pjs" style="color:#93C5FD; font-size:0.75rem; margin-top:0.5rem; font-weight:600; opacity:0.6;">
                    Jurusan Teknologi Informasi, Polinema
                </p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Login Form --}}
    <div class="login-right">
        <div class="login-card">
            {{-- Badge --}}
            <div style="text-align:center; margin-bottom:1rem;">
                <span class="pjs" style="display:inline-flex; align-items:center; gap:0.375rem; padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:rgba(0,59,122,0.08); color:#003B7A;">
                    <span style="width:0.375rem; height:0.375rem; border-radius:50%; display:inline-block; background:#F5A623;"></span>
                    Portal Magang JTI
                </span>
            </div>

            {{-- Heading --}}
            <h2 class="pjs" style="font-size:1.5rem; font-weight:800; text-align:center; margin:0 0 0.25rem 0; color:#003B7A;">
                Selamat Datang Kembali
            </h2>
            <p style="color:#9CA3AF; font-size:0.75rem; text-align:center; margin-bottom:1rem; line-height:1.5;">
                Masuk ke akun Anda untuk mengelola kegiatan magang.
            </p>

            {{-- Logo JTI --}}
            <div style="text-align:center; margin-bottom:1.25rem;">
                <div style="display:inline-flex; align-items:center; justify-content:center; width:88px; height:88px; border-radius:1rem; background:#F8FAFC; box-shadow:0 2px 12px rgba(0,43,86,0.10), 0 1px 3px rgba(0,0,0,0.06); border:1px solid #E2E8F0;">
                    <img src="{{ asset('images/logo-jti.png') }}" alt="Logo JTI Polinema" style="width:60px; height:auto; object-fit:contain;">
                </div>
            </div>

            {{-- Filament Form Content (slot) --}}
            {{ $slot }}



            {{-- Bottom Status --}}
            <div style="display:flex; align-items:center; justify-content:center; gap:1rem; margin-top:1rem; padding-top:0.75rem; border-top:1px solid #F1F5F9;">
                <div style="display:flex; align-items:center; gap:0.375rem;">
                    <span style="width:0.375rem; height:0.375rem; border-radius:50%; background:#10B981;"></span>
                    <span style="font-size:0.7rem; color:#9CA3AF;">v2.4.1</span>
                </div>
                <span style="color:#E5E7EB;">·</span>
                <span style="font-size:0.7rem; color:#9CA3AF;">Sistem Online</span>
                <span style="color:#E5E7EB;">·</span>
                <span style="font-size:0.7rem; color:#9CA3AF;">JTI Polinema © {{ date('Y') }}</span>
            </div>
            <div class="login-footer-links">
                <a href="/">← Kembali ke Beranda</a>
                <a href="#">Informasi & FAQ</a>
                <a href="#">Hubungi Admin</a>
            </div>
        </div>
    </div>
</div>



</x-filament-panels::layout.base>
