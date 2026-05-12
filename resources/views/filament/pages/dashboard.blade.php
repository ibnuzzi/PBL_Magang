<div class="fi-page">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        #custom-admin-dashboard {
            font-family: 'Outfit', sans-serif !important;
            color: #1e293b !important;
            background: #f1f5f9 !important;
            padding: 1.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
            min-height: 100vh !important;
        }

        .bg-brand-blue { background-color: #003870 !important; }
        .text-brand-blue { color: #003870 !important; }
        
        .dash-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 1.5rem !important;
            padding: 1.5rem !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .dash-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        .dash-stats-grid {
            display: grid !important;
            grid-template-columns: repeat(1, 1fr) !important;
            gap: 1.25rem !important;
        }
        @media (min-width: 640px) { .dash-stats-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (min-width: 768px) { .dash-stats-grid { grid-template-columns: repeat(3, 1fr) !important; } }
        @media (min-width: 1280px) { .dash-stats-grid { grid-template-columns: repeat(6, 1fr) !important; } }
        
        .stat-icon-wrapper {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 3.5rem !important;
            height: 3.5rem !important;
            border-radius: 1.25rem !important;
            margin-bottom: 1.25rem !important;
            transition: all 0.3s ease !important;
            box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06) !important;
        }
        .dash-card:hover .stat-icon-wrapper {
            transform: scale(1.1) rotate(5deg) !important;
        }

        .main-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
            margin-top: 1.5rem !important;
        }
        @media (min-width: 1024px) {
            .main-grid {
                grid-template-columns: repeat(12, 1fr) !important;
            }
            .col-5 { grid-column: span 5 / span 5 !important; }
            .col-7 { grid-column: span 7 / span 7 !important; }
        }
        
        .btn-premium {
            background: linear-gradient(135deg, #003870 0%, #002D5A 100%) !important;
            color: white !important;
            font-weight: 700 !important;
            border-radius: 1rem !important;
            padding: 0.85rem 1.75rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.6rem !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
            border: none !important;
            box-shadow: 0 10px 15px -3px rgba(0, 56, 112, 0.3) !important;
        }
        .btn-premium:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 56, 112, 0.4) !important;
            filter: brightness(1.2) !important;
        }

        .btn-amber {
            background: linear-gradient(135deg, #F5A623 0%, #D97706 100%) !important;
            box-shadow: 0 10px 15px -3px rgba(245, 166, 35, 0.3) !important;
        }
        .btn-amber:hover {
            box-shadow: 0 20px 25px -5px rgba(245, 166, 35, 0.4) !important;
        }
        
        .alert-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%) !important;
            border: 1px solid #fde68a !important;
            padding: 1.5rem !important;
            border-radius: 1.5rem !important;
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 1.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        .alert-box::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 6px !important;
            height: 100% !important;
            background: #fbbf24 !important;
        }

        .table-premium thead th {
            background: #f8fafc !important;
            color: #64748b !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            padding: 1.25rem 1.5rem !important;
            font-size: 0.65rem !important;
            border-bottom: 2px solid #f1f5f9 !important;
        }

        .table-premium tbody td {
            padding: 1.25rem 1.5rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px) !important;
            border-radius: 1.5rem !important;
            padding: 1.25rem 2rem !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        }

        .progress-bar-container {
            width: 100% !important;
            height: 0.75rem !important;
            background: #f1f5f9 !important;
            border-radius: 999px !important;
            overflow: hidden !important;
            box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.05) !important;
        }

        .badge-pill {
            padding: 0.35rem 1rem !important;
            border-radius: 9999px !important;
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.025em !important;
        }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>

    <div id="custom-admin-dashboard" class="space-y-8">
        <!-- Header -->
        <div class="header-glass flex flex-wrap items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-blue rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Admin Dashboard</h2>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Sistem Informasi Magang JTI Polinema
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:block text-right mr-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Waktu Server</p>
                    <p class="text-xs font-bold text-slate-700 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('H:i') }} WIB</p>
                </div>
                <button onclick="window.location.reload()" class="btn-premium px-6 py-3 shadow-blue-200">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Segarkan Data</span>
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="dash-stats-grid">
            @foreach($stats as $stat)
                <div class="dash-card group">
                    <div class="stat-icon-wrapper bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 group-hover:bg-{{ $stat['color'] }}-600 group-hover:text-white transition-all duration-300">
                        @if($stat['icon'] == 'users')
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @elseif($stat['icon'] == 'academic')
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        @elseif($stat['icon'] == 'briefcase')
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        @elseif($stat['icon'] == 'document')
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @elseif($stat['icon'] == 'book')
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @else
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $stat['value'] }}</h4>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">{{ $stat['title'] }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100/50 flex items-center justify-between">
                        <span class="badge-pill @if($stat['trend_dir'] == 'up') bg-emerald-100 text-emerald-700 @elseif($stat['trend_dir'] == 'down') bg-rose-100 text-rose-700 @else bg-slate-100 text-slate-600 @endif">
                            {{ $stat['trend'] }}
                        </span>
                        <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-{{ $stat['color'] }}-100 group-hover:text-{{ $stat['color'] }}-600 transition-colors">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Alert -->
        <div class="alert-box shadow-xl animate-float">
            <div class="flex items-center gap-6">
                <div class="flex items-center justify-center w-16 h-16 rounded-3xl bg-amber-400 text-amber-950 shadow-2xl shadow-amber-200 shrink-0">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h5 class="text-lg font-black text-amber-950">Pusat Perhatian: 17 Nilai Mahasiswa Menunggu</h5>
                    <p class="text-sm text-amber-800 font-semibold opacity-70 leading-relaxed max-w-2xl mt-1">Terdapat mahasiswa magang yang telah menyelesaikan masa studinya namun belum mendapatkan validasi nilai akhir. Segera koordinasikan dengan dosen penguji.</p>
                </div>
            </div>
            <a href="{{ route('filament.admin.resources.penilaians.index') }}" class="btn-premium btn-amber px-8 py-4 text-sm font-black shadow-amber-200">
                <span>Proses Sekarang</span>
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
        </div>

        <div class="main-grid">
            <!-- Parameter -->
            <div class="col-5">
                <div class="dash-card h-full flex flex-col p-8">
                    <div class="flex items-center justify-between gap-6 mb-10 pb-6 border-b border-slate-50">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Parameter Nilai</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Konfigurasi Bobot Akademik</p>
                        </div>
                        <div class="bg-emerald-600 text-white px-5 py-3 rounded-3xl shadow-2xl shadow-emerald-200 text-center">
                            <p class="text-[9px] font-black uppercase opacity-60 leading-none mb-1">Status</p>
                            <p class="text-xl font-black italic tracking-tighter">Balanced</p>
                        </div>
                    </div>
                    
                    <form wire:submit="saveParameter" class="space-y-10 flex-grow">
                        @foreach(['industri' => ['Nilai Industri', 'blue', 'Evaluasi dari supervisor mitra'], 'dosen' => ['Nilai Dosen', 'emerald', 'Evaluasi dari dosen pembimbing internal'], 'penguji' => ['Nilai Penguji', 'fuchsia', 'Hasil sidang laporan akhir magang']] as $key => $info)
                            <div class="space-y-4 group">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="text-base font-black text-slate-700 group-hover:text-{{ $info[1] }}-600 transition-colors">{{ $info[0] }}</h5>
                                        <p class="text-[11px] text-slate-400 font-bold opacity-60 italic mt-1">{{ $info[2] }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <input type="number" wire:model.live="bobot_{{ $key }}" class="w-20 h-12 text-center text-xl font-black bg-slate-50 border-0 rounded-2xl focus:ring-4 focus:ring-{{ $info[1] }}-100 transition-all shadow-inner">
                                        <span class="text-lg font-black text-slate-200">%</span>
                                    </div>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="h-full bg-{{ $info[1] }}-500 shadow-lg shadow-{{ $info[1] }}-100 transition-all duration-700 ease-out" style="width: {{ $this->{'bobot_'.$key} }}%"></div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="pt-8 mt-auto">
                            <button type="submit" class="w-full btn-premium py-5 justify-center text-base shadow-2xl">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>Perbarui Konfigurasi Bobot</span>
                            </button>
                            <div class="mt-6 flex items-center justify-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Sinkronisasi Terakhir: {{ $parameter_last_updated }}</p>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User List -->
            <div class="col-7">
                <div class="dash-card h-full overflow-hidden flex flex-col p-0 shadow-2xl border-0">
                    <div class="p-10 bg-white/40 backdrop-blur-xl border-b border-slate-50">
                        <div class="flex flex-wrap items-center justify-between gap-8">
                            <div>
                                <h3 class="text-3xl font-black text-slate-800 tracking-tighter">Manajemen User</h3>
                                <div class="flex items-center gap-3 mt-3">
                                    <div class="flex -space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 border-2 border-white"></div>
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 border-2 border-white"></div>
                                        <div class="w-6 h-6 rounded-full bg-fuchsia-100 border-2 border-white"></div>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-black uppercase tracking-widest">348 Total Akun Terdaftar</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 mt-4 lg:mt-0">
                                <a href="{{ route('filament.admin.resources.users.create') }}" class="btn-premium px-6 py-3 text-[11px] whitespace-nowrap shadow-blue-200">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Tambah Akun</span>
                                </a>
                                <a href="{{ route('filament.admin.resources.users.index') }}" class="flex items-center gap-3 px-6 py-3 bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-black rounded-2xl hover:bg-slate-200 transition-all shadow-sm whitespace-nowrap">
                                    <span>Database Lengkap</span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left table-premium">
                            <thead>
                                <tr>
                                    <th>Informasi User</th>
                                    <th>Level Akses</th>
                                    <th>Status Akun</th>
                                    <th class="text-center">Operasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50/70 transition-all group">
                                        <td>
                                            <div class="flex items-center gap-5">
                                                <div class="w-12 h-12 rounded-2xl bg-{{ $user['role_color'] }}-50 text-{{ $user['role_color'] }}-600 flex items-center justify-center font-black text-sm shadow-inner group-hover:bg-{{ $user['role_color'] }}-600 group-hover:text-white transition-all duration-300">
                                                    {{ $user['initial'] }}
                                                </div>
                                                <div class="space-y-0.5">
                                                    <div class="font-black text-slate-800 text-base tracking-tight">{{ $user['name'] }}</div>
                                                    <div class="text-xs text-slate-400 font-bold opacity-60">{{ $user['email'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-pill bg-{{ $user['role_color'] }}-50 text-{{ $user['role_color'] }}-700 border-{{ $user['role_color'] }}-200">
                                                {{ $user['role'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full {{ $user['status'] ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)]' : 'bg-slate-300' }}"></div>
                                                <span class="text-xs font-black {{ $user['status'] ? 'text-emerald-600' : 'text-slate-400' }}">
                                                    {{ $user['status'] ? 'ONLINE' : 'OFFLINE' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center justify-center gap-4">
                                                <button wire:click="toggleUserStatus({{ $user['id'] }})" class="w-10 h-10 flex items-center justify-center rounded-2xl {{ $user['status'] ? 'bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white' : 'bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white' }} transition-all duration-300 shadow-sm" title="{{ $user['status'] ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    @if($user['status'])
                                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                    @else
                                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                                    @endif
                                                </button>
                                                <a href="{{ route('filament.admin.resources.users.edit', ['record' => $user['id']]) }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-2xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm" title="Ubah Profil User">
                                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-8 bg-slate-50/80 backdrop-blur-md border-t border-slate-100 flex items-center justify-between">
                         <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em]">Administrator Terminal 2026</p>
                         <div class="flex gap-1.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400/30"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400/50"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400/80"></div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>