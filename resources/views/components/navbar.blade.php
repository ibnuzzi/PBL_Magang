<header
    class="sticky top-0 z-30 flex h-20 w-full items-center justify-between bg-white border-b border-slate-200 px-4 sm:px-6 shadow-sm">
    <!-- Left side -->
    <div class="flex items-center gap-4">
        <!-- Hamburger Menu -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-slate-500 hover:text-brand-blue md:hidden focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

        <div class="hidden sm:flex items-center text-sm font-medium text-slate-500">
            <span class="text-brand-blue font-bold">SiMagang JTI</span>
            <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span>{{ request()->is('admin*') ? 'Beranda Admin' : 'Beranda' }}</span>
        </div>
    </div>

    <!-- Right side -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Search -->
        <div class="relative hidden lg:block group">
            <input type="text"
                placeholder="{{ request()->is('admin*') ? 'Cari user, NIM, perusaha...' : 'Cari mahasiswa, NIM, perusaha...' }}"
                class="w-72 pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 hover:bg-white transition-all">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 transform -translate-y-1/2" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Notification -->
        <button
            class="relative p-2.5 text-slate-500 hover:text-brand-blue transition-colors rounded-xl border border-slate-200 hover:bg-slate-50 focus:outline-none">
            <span class="absolute top-2 right-2 w-2 h-2 bg-brand-yellow rounded-full"></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>

        <!-- Profile Dropdown -->
        <div class="flex items-center gap-3 pl-2 cursor-pointer hover:opacity-80 transition-opacity">
            @if(request()->is('admin*'))
                <div
                    class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center font-bold text-white text-sm shadow-sm ring-2 ring-white">
                    SA
                </div>
                <div class="hidden md:block text-sm">
                    <span class="block font-bold text-slate-800">Super Admin</span>
                    <div class="flex items-center gap-1">
                        <span class="block text-[11px] text-slate-500">Administrator</span>
                        <span
                            class="px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-bold rounded uppercase tracking-tighter">Admin</span>
                    </div>
                </div>
            @else
                <div
                    class="w-9 h-9 rounded-full bg-[#0B4A8F] flex items-center justify-center font-bold text-white text-sm shadow-sm ring-2 ring-white">
                    RD
                </div>
                <div class="hidden md:block text-sm">
                    <span class="block font-bold text-slate-800">Rizky Dwi Putra</span>
                    <span class="block text-[11px] text-slate-500">Mahasiswa</span>
                </div>
            @endif
            <svg class="w-4 h-4 text-slate-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
</header>