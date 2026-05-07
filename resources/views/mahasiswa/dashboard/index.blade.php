@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header / Breadcrumb -->
    <div class="flex items-center text-xs text-slate-500 font-medium mb-4">
        <a href="#" class="hover:text-[#0B4A8F] transition-colors">SiMagang JTI</a>
        <svg class="w-3 h-3 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-[#0B4A8F] font-semibold">Beranda</span>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <!-- Welcome Banner -->
    <div class="bg-[#0B4A8F] rounded-2xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-md border border-[#093d77] relative overflow-hidden">
        <!-- Abstract Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5 blur-2xl"></div>
        <div class="absolute bottom-0 right-32 -mb-16 w-40 h-40 rounded-full bg-[#F5A623] opacity-10 blur-xl"></div>
        
        <div class="relative z-10">
            <p class="text-blue-200 text-sm font-medium mb-1 flex items-center gap-2">
                Selamat datang kembali <span class="text-lg">👋</span>
            </p>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $user['name'] }}</h1>
            <div class="flex flex-wrap items-center gap-2 text-sm text-blue-100/80">
                <span>NIM: {{ $user['nim'] }}</span>
                <span class="w-1 h-1 rounded-full bg-blue-300"></span>
                <span>{{ $user['program_studi'] }}</span>
                <span class="w-1 h-1 rounded-full bg-blue-300"></span>
                <span>Semester {{ $user['semester'] }}</span>
            </div>
        </div>
        <div class="relative z-10 flex flex-col items-start md:items-end gap-2 bg-[#083b72] p-4 rounded-xl border border-[#1157a3]">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800/50 rounded-full border border-slate-700/50">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                <span class="text-white text-xs font-bold tracking-wide">{{ $user['status'] }}</span>
            </div>
            <p class="text-blue-100 text-xs font-medium">{{ $user['company'] }}</p>
        </div>
    </div>

    <!-- Status Pendaftaran Tracker -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Status Pendaftaran Magang</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $user['company'] }}</p>
            </div>
            <span class="px-3 py-1.5 bg-blue-50 text-[#0B4A8F] text-xs font-bold rounded-lg border border-blue-100">
                Sedang Diproses
            </span>
        </div>

        <div class="relative">
            <!-- Connecting Line -->
            <div class="absolute top-5 left-[12%] right-[12%] h-0.5 bg-slate-200 -z-0 hidden md:block"></div>
            <div class="absolute top-5 left-[12%] h-0.5 bg-emerald-500 -z-0 hidden md:block transition-all duration-500" style="width: 50%;"></div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center relative z-10">
                @foreach($approval_steps as $index => $step)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm mb-3 shadow-sm transition-all
                            {{ $step['state'] === 'completed' ? 'bg-emerald-500 text-white ring-4 ring-emerald-50' : 
                               ($step['state'] === 'current' ? 'bg-[#0B4A8F] text-white ring-4 ring-blue-50' : 'bg-white border-2 border-slate-200 text-slate-400') }}">
                            @if($step['state'] === 'completed')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <h4 class="text-sm font-bold {{ $step['state'] === 'pending' ? 'text-slate-400' : 'text-slate-800' }}">{{ $step['title'] }}</h4>
                        <p class="text-xs font-bold mt-1 {{ $step['state'] === 'completed' ? 'text-emerald-600' : ($step['state'] === 'current' ? 'text-[#F5A623]' : 'text-slate-400') }}">
                            {{ $step['status'] }}
                        </p>
                        <span class="text-[10px] text-slate-400 mt-0.5">{{ $step['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2.5 rounded-xl bg-blue-50 text-[#0B4A8F]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total_logbook'] }}</h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">Total Logbook</p>
                <div class="mt-2 text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    +3 minggu ini
                </div>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['menunggu_acc'] }}</h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">Menunggu ACC</p>
                <div class="mt-2 text-[10px] font-bold text-amber-600">
                    Perlu tindak lanjut
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['hari_berjalan'] }} <span class="text-sm font-semibold text-slate-400">hr</span></h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">Hari Berjalan</p>
                <div class="mt-2">
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ ($stats['hari_berjalan'] / $stats['hari_target']) * 100 }}%"></div>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium">dari {{ $stats['hari_target'] }} hari target</span>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-[0.03]">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
            <div class="flex justify-between items-start mb-2 relative z-10">
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-500 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['nilai_sementara'] }}<span class="text-base font-bold text-slate-400">/100</span></h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">Nilai Sementara</p>
                <div class="mt-2 text-[10px] font-bold text-[#F5A623] bg-amber-50 px-2 py-0.5 rounded w-max border border-amber-100">
                    Grade {{ $stats['grade'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Reminder Logbook -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="text-amber-500 mt-0.5">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-amber-800 font-bold text-sm">Jangan lupa isi logbook hari ini!</h4>
                <p class="text-amber-700 text-xs mt-0.5">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &mdash; Logbook belum diisi untuk hari ini</p>
            </div>
        </div>
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" class="flex-shrink-0">
            @csrf
            <!-- Hidden inputs as mock for quick logbook submit from dashboard -->
            <input type="hidden" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
            <input type="hidden" name="kegiatan" value="Kegiatan harian rutin">
            <button type="submit" class="bg-[#F5A623] hover:bg-[#e0951c] text-[#63430a] px-4 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">
                Isi Logbook Sekarang
            </button>
        </form>
    </div>

    <!-- 2 Columns Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Logbook Terbaru Table -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Logbook Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">5 entri terakhir</p>
                </div>
                <a href="{{ route('mahasiswa.logbook.index') }}" class="text-xs text-[#0B4A8F] font-bold hover:text-blue-800 flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            <div class="overflow-x-auto flex-1 p-2">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3 text-center">ACC Industri</th>
                            <th class="px-4 py-3 text-center">ACC Dosen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($logbooks as $logbook)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-700 text-xs">{{ $logbook['tanggal'] }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $logbook['hari'] }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-xs text-slate-600 font-medium whitespace-normal max-w-xs leading-snug">
                                        {{ $logbook['kegiatan'] }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($logbook['acc_industri'] === 'ACC')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            ACC
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($logbook['acc_dosen'] === 'ACC')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            ACC
                                        </span>
                                    @elseif($logbook['acc_dosen'] === 'Pending')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Revisi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dokumen Magang List -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-xl">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Dokumen Magang</h2>
                    <p class="text-xs text-slate-500 mt-0.5">4 dari 6 dokumen terunggah</p>
                </div>
                <div class="bg-sky-50 text-sky-600 px-2 py-1 rounded text-xs font-bold">
                    4/6
                </div>
            </div>
            <div class="p-4 flex-1">
                <div class="space-y-3">
                    @foreach($dokumen as $doc)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-700">{{ $doc['nama'] }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Diunggah {{ $doc['tanggal'] }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded">
                                {{ $doc['status'] }}
                            </span>
                        </div>
                    @endforeach
                    
                    <!-- Example Empty State -->
                    <div class="flex items-center justify-between p-3 rounded-xl border border-dashed border-slate-200 bg-white">
                        <div class="flex items-center gap-3 opacity-50">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-500">Laporan Akhir</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Belum diunggah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
