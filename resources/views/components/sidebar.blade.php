<aside
    class="flex flex-col w-[260px] bg-brand-blue text-white transition-all duration-300 z-50 flex-shrink-0 absolute inset-y-0 left-0 transform md:relative md:translate-x-0"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
    @click.away="if(window.innerWidth < 768) sidebarOpen = false">

    <!-- Logo -->
    <div class="flex items-center h-20 px-6 gap-3">
        <div class="bg-brand-yellow p-2 rounded-xl">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                </path>
            </svg>
        </div>
        <div>
            <h1 class="font-bold text-lg leading-tight tracking-wide">SiMagang JTI</h1>
            <p class="text-[10px] text-blue-200">Polinema - TI</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-1 custom-scrollbar">

        @if(request()->is('admin*'))
            <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-2 px-2">Master Data</div>

            <a href="{{ route('admin.dashboard.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard.*') ? 'bg-brand-yellow text-white shadow-sm font-semibold' : 'text-blue-100 hover:bg-white/10 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Beranda
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Manajemen User
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                Mitra Perusahaan
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                Parameter Penilaian
            </a>

            <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-6 px-2">Operasional</div>

            <a href="#"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Semua Pendaftaran
                </div>
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">23</span>
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                Logbook Monitor
            </a>

            <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-6 px-2">Output</div>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export Data
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Laporan
            </a>

            <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-6 px-2">Sistem</div>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan Sistem
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Log Aktivitas
            </a>
        @else
            <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-2 px-2">Menu Utama</div>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('mahasiswa.dashboard.*') ? 'bg-brand-yellow text-white shadow-sm font-semibold' : 'text-blue-100 hover:bg-white/10 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Beranda
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Pendaftaran Magang
            </a>

            <a href="#"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-brand-yellow text-white shadow-sm font-semibold' : 'text-blue-100 hover:bg-white/10 font-medium' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Logbook
                </div>
                <span class="bg-[#8b5c11] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">3</span>
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Dokumen
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                Penilaian
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-blue-100 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profil
            </a>
        @endif

        <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-3 mt-6 px-2">Dashboard Lain</div>

        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-sm text-blue-100 hover:bg-white/10 transition-colors">Dashboard
            Mahasiswa</a>
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-sm text-blue-100 hover:bg-white/10 transition-colors">Dashboard
            Dosen</a>
        <a href="#"
            class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-sm text-blue-100 hover:bg-white/10 transition-colors">Dashboard
            Koordinator</a>

    </nav>

    <!-- User Footer -->
    <div class="p-4 border-t border-blue-800">
        @if(request()->is('admin*'))
            <a href="#" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div
                    class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center font-bold text-white shadow-md">
                    SA
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">Super Admin</p>
                    <p class="text-[11px] text-blue-200 truncate">Administrator Sistem</p>
                </div>
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </a>
        @else
            <a href="#" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div
                    class="w-10 h-10 rounded-full bg-[#F5A623] flex items-center justify-center font-bold text-[#63430a] shadow-md">
                    RD
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">Rizky Dwi Putra</p>
                    <p class="text-[11px] text-blue-200 truncate">NIM: 2141720123</p>
                </div>
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </a>
        @endif
    </div>
</aside>

<!-- Mobile Overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden" x-transition.opacity
    @click="sidebarOpen = false"></div>