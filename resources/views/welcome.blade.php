@extends('layouts.app')  
  
@section('title', 'Beranda — SiMagang JTI Polinema')  
  
@push('styles')  
<style>  
    .hero-overlay {  
        background: linear-gradient(135deg, rgba(0,43,86,0.92) 0%, rgba(0,59,122,0.80) 60%, rgba(0,43,86,0.65) 100%);  
    }  
    .badge-accent { background-color: #F5A623; color: #7A4500; }  
    .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }  
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,59,122,0.12); }  
    .footer-bg { background: linear-gradient(135deg, #002856 0%, #003B7A 100%); }  
    .footer-link { font-size: 0.875rem; color: #93C5FD; transition: color 0.2s; text-decoration: none; }  
    .footer-link:hover { color: #F5A623; }  
    .section-alt { background-color: #F8FAFC; }  
    html { scroll-behavior: smooth; }  
    
    /* Marquee Slider */
    .slider-area {
        overflow: hidden;
        position: relative;
        width: 100%;
    }
    .slider-area::after, .slider-area::before {
        content: "";
        position: absolute;
        top: 0;
        width: 100px;
        height: 100%;
        z-index: 2;
    }
    .slider-area::after {
        right: 0;
        background: linear-gradient(to left, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
    }
    .slider-area::before {
        left: 0;
        background: linear-gradient(to right, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
    }
    @media (min-width: 768px) {
        .slider-area::after, .slider-area::before { width: 150px; }
    }
    .slider-track {
        display: flex;
        width: calc(200px * 12);
        animation: scroll 30s linear infinite;
    }
    .slider-track:hover {
        animation-play-state: paused;
    }
    .slide {
        width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 1.5rem;
    }
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(calc(-200px * 6)); }
    }
</style>  
@endpush  
  
@section('content')  
  
{{-- ==================== NAVBAR ==================== --}}  
<header data-design-id="navbar-header" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">  
    <div data-design-id="navbar-container" class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">  
  
        {{-- Logo --}}  
        <div data-design-id="navbar-logo-group" class="flex items-center gap-3">  
            <div data-design-id="navbar-logo-icon"  
                 class="w-10 h-10 rounded-lg flex items-center justify-center"  
                 style="background-color:#003B7A;">  
                <img src="{{ asset('images/logo-jti.png') }}" alt="Logo JTI Polinema" class="w-7 h-7">
            </div>  
            <div data-design-id="navbar-logo-text-group">  
                <div data-design-id="navbar-logo-title"  
                     class="font-extrabold text-base leading-tight pjs"  
                     style="color:#003B7A;">SiMagang JTI</div>  
                <div data-design-id="navbar-logo-subtitle"  
                     class="text-xs text-gray-500 leading-tight">Polinema · Teknologi Informasi</div>  
            </div>  
        </div>  
  
        {{-- Nav Links --}}  
        <nav data-design-id="navbar-nav" class="hidden md:flex items-center gap-8">  
            <a href=""           class="nav-link text-sm">Beranda</a>  
            <a href="#jenis-magang"                   class="nav-link text-sm">Jenis Magang</a>  
            <a href="#alur-pendaftaran"               class="nav-link text-sm">Alur Pendaftaran</a>  
            <a href="#fitur"                          class="nav-link text-sm">Fitur</a>  
            <a href="#footer"                         class="nav-link text-sm">Kontak</a>  
        </nav>  
  
        {{-- Auth Buttons --}}  
        <div data-design-id="navbar-auth-group" class="flex items-center gap-3">  
            @auth  
                <a href=""  
                   class="px-5 py-2 text-sm font-semibold rounded-lg transition-all hover:opacity-90 pjs"  
                   style="background-color:#003B7A; color:#fff;">  
                    Dashboard  
                </a>  
            @else  
                <a href="{{ route('login') }}"  
                   class="px-5 py-2 text-sm font-semibold rounded-lg border-2 transition-all pjs"  
                   style="border-color:#003B7A; color:#003B7A; background-color:rgba(0,59,122,0.06);">  
                    Masuk  
                </a>  
            @endauth  
        </div>  
    </div>  
</header>  
  
<main>  
  
{{-- ==================== HERO ==================== --}}  
<section data-design-id="hero-section"  
         class="relative overflow-hidden flex items-center"  
         style="min-height:680px;">  
  
    {{-- Background Image --}}  
    <div data-design-id="hero-bg-image" class="absolute inset-0">  
        <img data-design-id="hero-campus-photo"  
             src="https://sfile.ii.inc/image_search/e8b60efd86f6.jpg"  
             alt="Kampus Polinema Malang"  
             class="w-full h-full object-cover object-center">  
        <div data-design-id="hero-overlay" class="absolute inset-0 hero-overlay"></div>  
    </div>  
  
    {{-- Hero Content --}}  
    <div data-design-id="hero-content-container"  
         class="relative max-w-7xl mx-auto px-6 py-24 w-full">  
        <div data-design-id="hero-content-inner" class="max-w-2xl">  
  
            <div data-design-id="hero-badge"  
                 class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-6 badge-accent pjs">  
                <span class="w-1.5 h-1.5 rounded-full bg-amber-700 inline-block"></span>  
                Sistem Informasi Magang Resmi JTI Polinema  
            </div>  
  
            <h1 data-design-id="hero-title"  
                class="text-5xl font-extrabold text-white leading-tight mb-5 pjs">  
                Kelola Magang Lebih<br>  
                <span data-design-id="hero-title-accent" style="color:#F5A623;">Mudah, Cepat,</span><br>  
                dan Terstruktur  
            </h1>  
  
            <p data-design-id="hero-description"  
               class="text-blue-100 text-lg leading-relaxed mb-8 max-w-xl">  
                Platform digital terintegrasi untuk mahasiswa, dosen pembimbing, koordinator, dan pimpinan jurusan dalam mengelola seluruh proses magang di Jurusan Teknologi Informasi Polinema.  
            </p>  
  
            <div data-design-id="hero-cta-group" class="flex items-center gap-4">  
                <a data-design-id="hero-cta-primary"  
                   href="{{ route('login') }}"  
                   class="px-8 py-3.5 rounded-xl font-bold text-sm transition-all hover:opacity-90 shadow-lg pjs"  
                   style="background-color:#F5A623; color:#7A4500;">  
                    Masuk ke Sistem  
                </a>  
            </div>  
  
            {{-- Stats --}}  
            <div data-design-id="hero-stats-group"  
                 class="flex items-center gap-8 mt-12 pt-8 border-t border-white border-opacity-20">  
                <div data-design-id="hero-stat-1">  
                    <div class="text-3xl font-extrabold text-white pjs">1.200+</div>  
                    <div class="text-blue-200 text-sm mt-0.5">Mahasiswa Terdaftar</div>  
                </div>  
                <div class="h-10 w-px bg-white opacity-20"></div>  
                <div data-design-id="hero-stat-2">  
                    <div class="text-3xl font-extrabold text-white pjs">340+</div>  
                    <div class="text-blue-200 text-sm mt-0.5">Tempat Magang</div>  
                </div>  
                <div class="h-10 w-px bg-white opacity-20"></div>  
                <div data-design-id="hero-stat-3">  
                    <div class="text-3xl font-extrabold text-white pjs">48</div>  
                    <div class="text-blue-200 text-sm mt-0.5">Dosen Pembimbing</div>  
                </div>  
            </div>  
  
        </div>  
    </div>  
</section>  
  
{{-- ==================== 3 JENIS MAGANG ==================== --}}  
<section data-design-id="jenis-section" id="jenis-magang" class="py-20 bg-white">  
    <div data-design-id="jenis-container" class="max-w-7xl mx-auto px-6">  
  
        {{-- Header --}}  
        <div data-design-id="jenis-header" class="text-center mb-14">  
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 badge-accent pjs">  
                Program Magang  
            </div>  
            <h2 class="text-4xl font-extrabold mb-4 pjs" style="color:#003B7A;">Tiga Jenis Magang JTI</h2>  
            <p class="text-gray-500 text-lg max-w-xl mx-auto">  
                Pilih jenis magang sesuai dengan kebutuhan akademik dan minat pengembangan karirmu.  
            </p>  
        </div>  
  
        <div data-design-id="jenis-cards-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">  
  
            {{-- Card: Magang Mandiri --}}  
            <div data-design-id="jenis-card-mandiri"  
                 class="relative bg-white rounded-2xl border border-gray-100 shadow-sm p-8 card-hover overflow-hidden">  
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl"  
                     style="background:linear-gradient(90deg,#003B7A,#1a6cb8);"></div>  
  
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6"  
                     style="background-color:rgba(0,59,122,0.08);">  
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"  
                         style="color:#003B7A;">  
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>  
                    </svg>  
                </div>  
  
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mb-3 badge-accent pjs">  
                    Fleksibel  
                </div>  
                <h3 class="text-xl font-bold mb-3 pjs" style="color:#003B7A;">Magang Mandiri</h3>  
                <p class="text-gray-500 text-sm leading-relaxed mb-6">  
                    Mahasiswa mencari dan menentukan sendiri tempat magang sesuai minat dan bidang yang ingin dikembangkan. Tidak diakui SKS namun difasilitasi surat pengantar resmi dari kampus.  
                </p>  
  
                <ul class="space-y-2">  
                    @foreach([  
                        'Bebas memilih perusahaan/instansi',  
                        'Difasilitasi surat pengantar kampus',  
                        'Wajib laporan & logbook digital',  
                    ] as $item)  
                    <li class="flex items-center gap-2 text-sm text-gray-600">  
                        <span class="w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0"  
                              style="background-color:rgba(0,59,122,0.10);">  
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"  
                                 stroke="currentColor" style="color:#003B7A;">  
                                <path stroke-linecap="round" stroke-linejoin="round"  
                                      stroke-width="3" d="M5 13l4 4L19 7"/>  
                            </svg>  
                        </span>  
                        {{ $item }}  
                    </li>  
                    @endforeach  
                </ul>  
            </div>  
  
            {{-- Card: Magang Pilihan --}}  
            <div data-design-id="jenis-card-pilihan"  
                 class="relative rounded-2xl shadow-xl p-8 card-hover overflow-hidden text-white"  
                 style="background:linear-gradient(145deg,#003B7A 0%,#0050a8 100%);">  
                <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10"  
                     style="background:#F5A623;"></div>  
                <div class="absolute top-4 right-4 px-2.5 py-1 rounded-full text-xs font-bold badge-accent pjs">  
                    Semester 6  
                </div>  
  
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6"  
                     style="background:rgba(255,255,255,0.15);">  
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24"  
                         stroke="currentColor">  
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>  
                    </svg>  
                </div>  
  
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mb-3 pjs"  
                     style="background:rgba(255,255,255,0.20); color:white;">  
                    Terseleksi  
                </div>  
                <h3 class="text-xl font-bold mb-3 text-white pjs">Magang Pilihan</h3>  
                <p class="text-blue-100 text-sm leading-relaxed mb-6">  
                    Magang di perusahaan mitra resmi Polinema/CTI. Bersifat konversi nilai SKS. Hanya untuk mahasiswa semester 6 yang dinilai sudah kompeten oleh koordinator berdasarkan IPK dan portofolio.  
                </p>  
  
                <ul class="space-y-2">  
                    @foreach([  
                        'Perusahaan mitra Polinema/CTI terkurasi',  
                        'Konversi nilai SKS akademik',  
                        'Seleksi berbasis IPK & kompetensi',  
                    ] as $item)  
                    <li class="flex items-center gap-2 text-sm text-blue-100">  
                        <span class="w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0"  
                              style="background:rgba(245,166,35,0.30);">  
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"  
                                 stroke="#F5A623">  
                                <path stroke-linecap="round" stroke-linejoin="round"  
                                      stroke-width="3" d="M5 13l4 4L19 7"/>  
                            </svg>  
                        </span>  
                        {{ $item }}  
                    </li>  
                    @endforeach  
                </ul>  
            </div>  
  
            {{-- Card: Magang Wajib --}}  
            <div data-design-id="jenis-card-wajib"  
                 class="relative bg-white rounded-2xl border border-gray-100 shadow-sm p-8 card-hover overflow-hidden">  
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl"  
                     style="background:linear-gradient(90deg,#F5A623,#f8c05a);"></div>  
  
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6"  
                     style="background-color:rgba(245,166,35,0.10);">  
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24"  
                         stroke="currentColor" style="color:#c47f00;">  
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>  
                    </svg>  
                </div>  
  
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mb-3 pjs"  
                     style="background:rgba(245,166,35,0.15); color:#7A4500;">  
                    Semester 7  
                </div>  
                <h3 class="text-xl font-bold mb-3 pjs" style="color:#003B7A;">Magang Wajib</h3>  
                <p class="text-gray-500 text-sm leading-relaxed mb-6">  
                    Semua mahasiswa semester 7 wajib mengikuti. Merupakan bagian dari kurikulum akademik dan menjadi syarat kelulusan. Hanya boleh di mitra resmi Polinema/CTI.  
                </p>  
  
                <ul class="space-y-2">  
                    @foreach([  
                        'Wajib seluruh mahasiswa semester 7',  
                        'Diakui sebagai SKS akademik',  
                        'Presentasi & laporan akhir',  
                    ] as $item)  
                    <li class="flex items-center gap-2 text-sm text-gray-600">  
                        <span class="w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0"  
                              style="background-color:rgba(245,166,35,0.15);">  
                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24"  
                                 stroke="#c47f00">  
                                <path stroke-linecap="round" stroke-linejoin="round"  
                                      stroke-width="3" d="M5 13l4 4L19 7"/>  
                            </svg>  
                        </span>  
                        {{ $item }}  
                    </li>  
                    @endforeach  
                </ul>  
            </div>  
  
        </div>{{-- /grid --}}  
    </div>  
</section>  
  
{{-- ==================== ALUR PENDAFTARAN ==================== --}}  
<section data-design-id="alur-section" id="alur-pendaftaran" class="py-20 section-alt">  
    <div data-design-id="alur-container" class="max-w-7xl mx-auto px-6">  
  
        {{-- Header --}}  
        <div data-design-id="alur-header" class="text-center mb-14">  
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 badge-accent pjs">  
                Alur Persetujuan  
            </div>  
            <h2 class="text-4xl font-extrabold mb-4 pjs" style="color:#003B7A;">  
                Proses Pendaftaran Magang  
            </h2>  
            <p class="text-gray-500 text-lg max-w-xl mx-auto">  
                Pengajuan magang melalui sistem approval berjenjang secara digital — cepat, transparan, dan terlacak.  
            </p>  
        </div>  
  
        {{-- Steps --}}  
        <div data-design-id="alur-steps-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6">  
  
            @php  
            $steps = [  
                [  
                    'num'   => 1,  
                    'title' => 'Mahasiswa',  
                    'desc'  => 'Upload dokumen & isi data pengajuan magang melalui sistem',  
                    'tag'   => 'Upload Dokumen',  
                    'gold'  => false,  
                ],  
                [  
                    'num'   => 2,  
                    'title' => 'Koordinator',  
                    'desc'  => 'Koordinator magang mereview dan memverifikasi kelengkapan dokumen',  
                    'tag'   => 'Verifikasi Dokumen',  
                    'gold'  => false,  
                ],  
                [  
                    'num'   => 3,  
                    'title' => 'KPS / Kajur',  
                    'desc'  => 'Ketua Program Studi dan Kajur memberikan persetujuan resmi',  
                    'tag'   => 'Persetujuan Resmi',  
                    'gold'  => false,  
                ],  
                [  
                    'num'   => 4,  
                    'title' => 'Wadir 1',  
                    'desc'  => 'Wakil Direktur 1 memberikan persetujuan final dan surat resmi diterbitkan',  
                    'tag'   => 'Surat Diterbitkan',  
                    'gold'  => true,  
                ],  
            ];  
            @endphp  
  
            @foreach($steps as $step)  
            <div data-design-id="alur-step-{{ $step['num'] }}"  
                 class="flex flex-col items-center text-center">  
  
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-4 shadow-md relative z-10"  
                     style="{{ $step['gold']  
                         ? 'background:linear-gradient(135deg,#F5A623 0%,#e8940f 100%);'  
                         : 'background:#003B7A;' }}">  
  
                    @if($step['num'] === 1)  
                        <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>  
                        </svg>  
                    @elseif($step['num'] === 2)  
                        <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>  
                        </svg>  
                    @elseif($step['num'] === 3)  
                        <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>  
                        </svg>  
                    @else  
                        <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="currentColor"  
                             style="color:#7A4500;">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>  
                        </svg>  
                    @endif  
  
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-xs font-bold  
                                 flex items-center justify-center shadow-sm pjs"  
                          style="{{ $step['gold']  
                              ? 'background:#003B7A; color:white;'  
                              : 'background:#F5A623; color:#7A4500;' }}">  
                        {{ $step['num'] }}  
                    </span>  
                </div>  
  
                <h4 class="font-bold text-base mb-1 pjs" style="color:#003B7A;">{{ $step['title'] }}</h4>  
                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>  
  
                <div class="mt-3 px-3 py-1 rounded-full text-xs font-medium pjs  
                            {{ $step['gold'] ? 'badge-accent' : '' }}"  
                     style="{{ !$step['gold'] ? 'background:rgba(0,59,122,0.08); color:#003B7A;' : '' }}">  
                    {{ $step['tag'] }}  
                </div>  
            </div>  
            @endforeach  
  
        </div>{{-- /steps --}}  
  
        {{-- Output Surat --}}  
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">  
            <div class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">  
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"  
                     style="background-color:rgba(0,59,122,0.08);">  
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"  
                         style="color:#003B7A;">  
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>  
                    </svg>  
                </div>  
                <div>  
                    <h4 class="font-bold text-base mb-1 pjs" style="color:#003B7A;">Surat Pengantar</h4>  
                    <p class="text-gray-500 text-sm leading-relaxed">  
                        Dikeluarkan oleh kampus setelah Wadir 1 menyetujui pengajuan magang. Dikirimkan ke perusahaan sebagai permohonan resmi.  
                    </p>  
                </div>  
            </div>  
            <div class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">  
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"  
                     style="background-color:rgba(245,166,35,0.10);">  
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"  
                         style="color:#c47f00;">  
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                              d="M5 13l4 4L19 7M9 12l2 2 4-4"/>  
                    </svg>  
                </div>  
                <div>  
                    <h4 class="font-bold text-base mb-1 pjs" style="color:#003B7A;">Surat LOA (Letter of Acceptance)</h4>  
                    <p class="text-gray-500 text-sm leading-relaxed">  
                        Dikeluarkan oleh industri/perusahaan sebagai tanda bahwa mahasiswa resmi diterima magang. Wajib diunggah ke sistem.  
                    </p>  
                </div>  
            </div>  
        </div>  
  
        {{-- Persyaratan Dokumen --}}  
        <div data-design-id="persyaratan-section"  
             class="mt-10 bg-white rounded-2xl border border-gray-100 shadow-sm p-8">  
            <div class="flex flex-col md:flex-row gap-8 items-start">  
  
                <div class="md:w-1/3">  
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"  
                         style="background-color:rgba(0,59,122,0.08);">  
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"  
                             style="color:#003B7A;">  
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>  
                        </svg>  
                    </div>  
                    <h3 class="text-xl font-bold mb-2 pjs" style="color:#003B7A;">Persyaratan Dokumen</h3>  
                    <p class="text-gray-500 text-sm leading-relaxed">  
                        Semua dokumen diunggah secara digital melalui sistem. Format PDF, maksimal 5 MB per dokumen.  
                    </p>  
                </div>  
  
                <div class="md:w-2/3 grid grid-cols-2 md:grid-cols-3 gap-3">  
                    @foreach([  
                        'Proposal Magang',  
                        'Surat Integritas',  
                        'Izin Orang Tua',  
                        'CV / Riwayat Hidup',  
                        'Portfolio Karya',  
                        'KHS Terakhir',  
                    ] as $doc)  
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">  
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"  
                             style="background:rgba(0,59,122,0.10);">  
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"  
                                 stroke="currentColor" style="color:#003B7A;">  
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>  
                            </svg>  
                        </div>  
                        <span class="text-xs font-medium text-gray-700">{{ $doc }}</span>  
                    </div>  
                    @endforeach  
                </div>  
  
            </div>  
        </div>  
  
    </div>  
</section>  
  
{{-- ==================== FITUR UNGGULAN ==================== --}}  
<section data-design-id="fitur-section" id="fitur" class="py-20 bg-white">  
    <div data-design-id="fitur-container" class="max-w-7xl mx-auto px-6">  
  
        <div data-design-id="fitur-header" class="text-center mb-14">  
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 badge-accent pjs">  
                Fitur Sistem  
            </div>  
            <h2 class="text-4xl font-extrabold mb-4 pjs" style="color:#003B7A;">  
                Fitur Unggulan SiMagang  
            </h2>  
            <p class="text-gray-500 text-lg max-w-xl mx-auto">  
                Sistem terintegrasi yang memudahkan seluruh proses magang dari pendaftaran hingga penilaian akhir.  
            </p>  
        </div>  
  
        <div data-design-id="fitur-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">  
  
            @php  
            $features = [  
                [  
                    'title' => 'Logbook Digital Harian',  
                    'desc'  => 'Diisi harian oleh mahasiswa dengan tanggal auto-generate sehingga tidak bisa diisi mundur. Di-ACC mingguan oleh supervisor industri via link WhatsApp tanpa perlu login, lalu diteruskan ke dosen pembimbing. Tersedia opsi upload tanda tangan manual sebagai fallback.',  
                    'tag'   => 'Auto-generate · Anti-backdate',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',  
                ],  
                [  
                    'title' => 'Penilaian Dinamis & Export Excel',  
                    'desc'  => 'Nilai akhir dihitung dari tiga komponen: industri, dosen pembimbing, dan penguji. Persentase bobot bersifat dinamis dan dapat diubah admin setiap tahun sesuai kebutuhan kurikulum. Output dapat diekspor ke Excel untuk pengolahan lanjutan oleh tim KPS/kurikulum.',  
                    'tag'   => 'Bobot fleksibel · Export Excel',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',  
                ],  
                [  
                    'title' => 'Notifikasi WhatsApp Supervisor',  
                    'desc'  => 'Supervisor industri tidak perlu membuat akun. Sistem otomatis mengirim link via WhatsApp ke nomor supervisor. Supervisor klik link → lihat PDF logbook → klik Setuju → otomatis ter-ACC di sistem. Solusi praktis untuk kendala akun digital di perusahaan.',  
                    'tag'   => 'Tanpa akun supervisor',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',  
                ],  
                [  
                    'title' => 'Tracking Pendaftaran Real-time',  
                    'desc'  => 'Mahasiswa hanya boleh mendaftar ke satu perusahaan dalam satu waktu — harus menyelesaikan seleksi terlebih dahulu. Status persetujuan berjenjang (Koordinator → KPS → Kajur → Wadir 1) dapat dipantau langsung. Surat Pengantar dan LOA tersimpan otomatis.',  
                    'tag'   => '1 perusahaan per sesi · Real-time',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',  
                ],  
                [  
                    'title' => 'Approval Berjenjang Digital',  
                    'desc'  => 'Alur persetujuan Koordinator → KPS → Kajur → Wadir 1 dilakukan secara digital tanpa tatap muka. Setiap pejabat mendapat notifikasi dan dapat menyetujui atau menolak dengan keterangan. Riwayat approval tersimpan lengkap sebagai audit trail.',  
                    'tag'   => '4 level approval · Audit trail',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',  
                ],  
                [  
                    'title' => 'Multi-role & Manajemen Mitra',  
                    'desc'  => 'Sistem mendukung role Mahasiswa, Dosen Pembimbing, Koordinator, KPS, Kajur, Wadir 1, dan Admin. Daftar mitra resmi Polinema/CTI dikelola oleh admin. Terintegrasi dengan Simagang Polinema untuk pencatatan institusional level Polinema.',  
                    'tag'   => 'Multi-role · Mitra resmi Polinema',  
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',  
                ],  
            ];  
            @endphp  
  
            @foreach($features as $f)  
            <div data-design-id="fitur-card"  
                 class="flex gap-5 bg-gray-50 rounded-2xl p-6 border border-gray-100 card-hover">  
                <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"  
                     style="background:#003B7A;">  
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24"  
                         stroke="currentColor">  
                        {!! $f['icon'] !!}  
                    </svg>  
                </div>  
                <div>  
                    <h4 class="text-lg font-bold mb-2 pjs" style="color:#003B7A;">{{ $f['title'] }}</h4>  
                    <p class="text-gray-500 text-sm leading-relaxed mb-3">{{ $f['desc'] }}</p>  
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium"  
                         style="background:rgba(0,59,122,0.08); color:#003B7A;">  
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#F5A623;"></span>  
                        {{ $f['tag'] }}  
                    </div>  
                </div>  
            </div>  
            @endforeach  
  
        </div>{{-- /fitur-grid --}}  
    </div>  
</section>  

{{-- ==================== SLIDER MITRA ==================== --}}
<section class="py-12 bg-white border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 mb-8 text-center">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest pjs mb-2">Mitra Industri SiMagang</h3>
        <p class="text-xs text-gray-400">Telah bekerja sama dengan berbagai perusahaan terkemuka untuk program magang Polinema</p>
    </div>
    <div class="slider-area max-w-7xl mx-auto">
        <div class="slider-track">
            {{-- Original Set --}}
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Mitra Google" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Mitra Microsoft" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Mitra Amazon" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg" alt="Mitra Cisco" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="Mitra IBM" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Telkom_Indonesia_2013.svg" alt="Mitra Telkom" class="h-10 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            
            {{-- Duplikat untuk efek infinite scroll --}}
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Mitra Google" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Mitra Microsoft" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Mitra Amazon" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Cisco_logo_blue_2016.svg" alt="Mitra Cisco" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="Mitra IBM" class="h-8 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
            <div class="slide"><img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Telkom_Indonesia_2013.svg" alt="Mitra Telkom" class="h-10 max-w-full opacity-50 hover:opacity-100 transition-opacity grayscale hover:grayscale-0"></div>
        </div>
    </div>
</section>  
  
{{-- ==================== KETENTUAN PENTING ==================== --}}  
<section class="py-16 section-alt">  
    <div class="max-w-7xl mx-auto px-6">  
        <div class="text-center mb-10">  
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 badge-accent pjs">  
                Ketentuan  
            </div>  
            <h2 class="text-3xl font-extrabold pjs" style="color:#003B7A;">Ketentuan Mitra & Pendaftaran</h2>  
        </div>  
  
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">  
            @php  
            $rules = [  
                [  
                    'icon'  => '🏢',  
                    'title' => 'Mitra Resmi Polinema',  
                    'desc'  => 'Magang Pilihan dan Wajib (SKS) hanya boleh dilakukan di perusahaan mitra resmi Polinema atau CTI. Tidak boleh di sembarang perusahaan.',  
                    'color' => '#003B7A',  
                ],  
                [  
                    'icon'  => '📋',  
                    'title' => 'Satu Perusahaan Sekaligus',  
                    'desc'  => 'Mahasiswa tidak boleh mendaftar ke lebih dari satu perusahaan dalam satu waktu. Harus menyelesaikan seleksi terlebih dahulu sebelum mendaftar ke perusahaan lain.',  
                    'color' => '#c47f00',  
                ],  
                [  
                    'icon'  => '📅',  
                    'title' => 'Semester 6 & 7',  
                    'desc'  => 'Sistem magang hanya dapat diakses oleh mahasiswa semester 6 (Magang Pilihan) dan semester 7 (Magang Wajib). Sesuai kebijakan Portal JTI Polinema.',  
                    'color' => '#003B7A',  
                ],  
            ];  
            @endphp  
  
            @foreach($rules as $rule)  
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">  
                <div class="text-3xl mb-4">{{ $rule['icon'] }}</div>  
                <h4 class="font-bold text-base mb-2 pjs" style="color:{{ $rule['color'] }};">  
                    {{ $rule['title'] }}  
                </h4>  
                <p class="text-gray-500 text-sm leading-relaxed">{{ $rule['desc'] }}</p>  
            </div>  
            @endforeach  
        </div>  
    </div>  
</section>  
  
{{-- ==================== CTA SECTION ==================== --}}  
<section class="py-20" style="background:linear-gradient(135deg,#002856 0%,#003B7A 100%);">  
    <div class="max-w-4xl mx-auto px-6 text-center">  
  
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-6 badge-accent pjs">  
            <span class="w-1.5 h-1.5 rounded-full bg-amber-700 inline-block"></span>  
            Mulai Sekarang  
        </div>  
  
        <h2 class="text-4xl font-extrabold text-white mb-4 pjs">  
            Siap Mulai Perjalanan Magangmu?  
        </h2>  
        <p class="text-blue-200 text-lg mb-8 max-w-2xl mx-auto">  
            Kelola seluruh proses magang dengan lebih mudah, terstruktur, dan transparan bersama SiMagang JTI Polinema.  
        </p>  
  
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">  
            <a href="{{ route('login') }}"  
               class="px-8 py-4 rounded-xl font-bold text-sm hover:opacity-90 shadow-lg pjs w-full sm:w-auto text-center"  
               style="background-color:#F5A623; color:#7A4500;">  
                Masuk ke Sistem  
            </a>  
        </div>  
  
        <div class="flex items-center justify-center gap-8 mt-12 pt-8 border-t border-white border-opacity-20">  
            <div class="flex items-center gap-2 text-blue-200 text-sm">  
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#F5A623">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>  
                </svg>  
                Pendaftaran 100% Digital  
            </div>  
            <div class="flex items-center gap-2 text-blue-200 text-sm">  
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#F5A623">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>  
                </svg>  
                Logbook Real-time  
            </div>  
            <div class="flex items-center gap-2 text-blue-200 text-sm">  
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#F5A623">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>  
                </svg>  
                Penilaian Transparan  
            </div>  
        </div>  
  
    </div>  
</section>  
  
</main>  
  
{{-- ==================== FOOTER ==================== --}}  
<footer id="footer" class="footer-bg text-white pt-14 pb-8">  
    <div class="max-w-7xl mx-auto px-6">  
  
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">  
  
            {{-- Brand --}}  
            <div class="md:col-span-2">  
                <div class="flex items-center gap-3 mb-5">  
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"  
                         style="background:rgba(255,255,255,0.10);">  
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24"  
                             stroke="currentColor" stroke-width="2">  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M12 14l9-5-9-5-9 5 9 5z"/>  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M12 14l6.16-3.422A12.083 12.083 0 0121 13c0 3.866-3.582 7-8 7s-8-3.134-8-7a12.083 12.083 0 012.84-1.422L12 14z"/>  
                        </svg>  
                    </div>  
                    <div>  
                        <div class="font-extrabold text-lg pjs">SiMagang JTI</div>  
                        <div class="text-blue-300 text-xs">Politeknik Negeri Malang</div>  
                    </div>  
                </div>  
                <p class="text-blue-200 text-sm leading-relaxed max-w-sm mb-5">  
                    Sistem Informasi Magang resmi Jurusan Teknologi Informasi Politeknik Negeri Malang. Mengelola proses magang secara digital, transparan, dan efisien.  
                </p>  
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg w-fit"  
                     style="background:rgba(245,166,35,0.15); border:1px solid rgba(245,166,35,0.3);">  
                    <span class="w-2 h-2 rounded-full" style="background:#10B981;"></span>  
                    <span class="text-xs text-blue-200">Sistem Online · v2.4.1</span>  
                </div>  
            </div>  
  
            {{-- Navigasi --}}  
            <div>  
                <h4 class="font-bold text-sm mb-4 pjs" style="color:#F5A623;">Navigasi</h4>  
                <ul class="space-y-2.5">  
                    <li><a href="}}" class="footer-link">Beranda</a></li>  
                    <li><a href="#jenis-magang"         class="footer-link">Jenis Magang</a></li>  
                    <li><a href="#alur-pendaftaran"     class="footer-link">Alur Pendaftaran</a></li>  
                    <li><a href="#fitur"                class="footer-link">Fitur Sistem</a></li>  
                    <li><a href="{{ route('login') }}"  class="footer-link">Login</a></li>  
                    <li><a href=" }}" class="footer-link">Daftar Akun</a></li>  
                </ul>  
            </div>  
  
            {{-- Kontak --}}  
            <div>  
                <h4 class="font-bold text-sm mb-4 pjs" style="color:#F5A623;">Kontak</h4>  
                <ul class="space-y-3">  
                    <li class="flex items-start gap-2.5 text-blue-200 text-sm">  
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"  
                             stroke="currentColor" stroke-width="2">  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>  
                        </svg>  
                        Gedung Teknologi Informasi,<br>Jl. Soekarno Hatta No.9,<br>Malang, Jawa Timur 65141  
                    </li>  
                    <li class="flex items-center gap-2.5 text-blue-200 text-sm">  
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"  
                             stroke="currentColor" stroke-width="2">  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>  
                        </svg>  
                        <a href="mailto:magang@jti.polinema.ac.id" class="footer-link">  
                            magang@jti.polinema.ac.id  
                        </a>  
                    </li>  
                    <li class="flex items-center gap-2.5 text-blue-200 text-sm">  
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"  
                             stroke="currentColor" stroke-width="2">  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>  
                        </svg>  
                        (0341) 404-424 ext. 2xx  
                    </li>  
                    <li class="flex items-center gap-2.5 text-blue-200 text-sm">  
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24"  
                             stroke="currentColor" stroke-width="2">  
                            <path stroke-linecap="round" stroke-linejoin="round"  
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>  
                        </svg>  
                        Senin – Jumat, 08.00 – 16.00 WIB  
                    </li>  
                </ul>  
            </div>  
  
        </div>{{-- /grid --}}  
  
        {{-- Divider & Bottom --}}  
        <div class="border-t border-white border-opacity-10 pt-6  
                    flex flex-col md:flex-row items-center justify-between gap-4">  
            <p class="text-blue-300 text-xs text-center md:text-left">  
                © {{ date('Y') }} Jurusan Teknologi Informasi — Politeknik Negeri Malang.  
                All rights reserved.  
            </p>  
            <div class="flex items-center gap-6">  
                <a href="#" class="text-xs text-blue-300 hover:text-yellow-400 transition-colors">  
                    Kebijakan Privasi  
                </a>  
                <a href="#" class="text-xs text-blue-300 hover:text-yellow-400 transition-colors">  
                    Syarat & Ketentuan  
                </a>  
                <a href="#" class="text-xs text-blue-300 hover:text-yellow-400 transition-colors">  
                    Bantuan  
                </a>  
            </div>  
        </div>  
  
    </div>  
</footer>  
  
@endsection  
  
@push('scripts')  
<script>  
    // Smooth scroll untuk anchor links  
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {  
        anchor.addEventListener('click', function(e) {  
            const target = document.querySelector(this.getAttribute('href'));  
            if (target) {  
                e.preventDefault();  
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });  
            }  
        });  
    });  
  
    // Navbar shadow on scroll  
    window.addEventListener('scroll', () => {  
        const navbar = document.querySelector('header');  
        if (window.scrollY > 10) {  
            navbar.classList.add('shadow-md');  
        } else {  
            navbar.classList.remove('shadow-md');  
        }  
    });  
</script>  
@endpush  