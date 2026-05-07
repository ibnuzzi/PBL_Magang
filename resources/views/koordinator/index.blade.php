@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6 max-w-[1600px] mx-auto pb-10">
    
    <!-- Header Page & Alert -->
    <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-6">
        <div class="flex-1">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 flex items-center gap-2">
                Selamat Pagi, Dr. Rini <span class="text-2xl md:text-3xl">👋</span>
            </h1>
            <p class="text-sm text-slate-500 mt-2 font-medium">Selasa, 18 Juni 2024 - Semester Genap 2023/2024</p>
        </div>
        
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4 xl:max-w-xl shadow-sm w-full xl:w-auto">
            <div class="flex items-center gap-3 flex-1">
                <div class="bg-amber-100 p-2 rounded-lg text-amber-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">8 Pendaftaran Menunggu Verifikasi Hari Ini</h4>
                    <p class="text-[11px] md:text-xs text-slate-600 mt-0.5 leading-relaxed">3 di antaranya sudah menunggu lebih dari 2 hari. Segera tinjau antrian verifikasi.</p>
                </div>
            </div>
            <button class="shrink-0 bg-brand-yellow hover:bg-yellow-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-colors shadow-sm w-full sm:w-auto whitespace-nowrap">
                Tinjau Sekarang
            </button>
        </div>
    </div>

    <!-- Stats Cards (6 box) -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-6">

        @foreach($stats as $stat)
            <div class="bg-white rounded-xl p-4 md:p-5 border border-slate-200 border-t-4 {{ $stat['border'] }} shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all h-full">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full {{ $stat['iconBg'] }} {{ $stat['iconColor'] }} flex items-center justify-center mb-3 md:mb-4 shrink-0">
                    @if($stat['icon'] == 'users')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    @elseif($stat['icon'] == 'clock')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($stat['icon'] == 'check-square')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($stat['icon'] == 'x-circle')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($stat['icon'] == 'briefcase')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    @elseif($stat['icon'] == 'check-circle')
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                
                <div class="flex-1">
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stat['value'] }}</h3>
                    <p class="text-[11px] md:text-xs font-semibold text-slate-500 mt-1 mb-2">{{ $stat['title'] }}</p>
                </div>
                
                <div class="text-[10px] md:text-[11px] font-bold {{ $stat['trendColor'] }} flex items-center gap-1 mt-auto">
                    @if(strpos($stat['trend'], '+') !== false && strpos($stat['trend'], '↓') === false)
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    @endif
                    {{ str_replace('+', '', $stat['trend']) }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Antrian Verifikasi Table -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col border-t-4 border-t-brand-blue h-full">
            
            @if(session('success'))
            <div class="mx-5 md:mx-6 mt-4 mb-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-xs font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="px-5 md:px-6 py-4 md:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white">
                <div>
                    <h2 class="text-sm md:text-[15px] font-bold text-slate-800">Antrian Verifikasi Pendaftaran</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5 md:mt-1">8 pendaftaran menunggu ditinjau - diurutkan berdasarkan prioritas</p>
                </div>
                <button class="text-[11px] md:text-xs text-brand-blue font-bold hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 w-full sm:w-auto">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
            
            <!-- Tabs -->
            <div class="px-5 md:px-6 border-b border-slate-100 flex gap-2 py-3 overflow-x-auto custom-scrollbar">
                <button class="px-4 py-1.5 rounded-full bg-brand-blue text-white text-[11px] md:text-xs font-bold whitespace-nowrap">Semua</button>
                <button class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-[11px] md:text-xs font-semibold whitespace-nowrap flex items-center gap-2 transition-colors">
                    Menunggu
                    <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md text-[10px]">8</span>
                </button>
                <button class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-[11px] md:text-xs font-semibold whitespace-nowrap transition-colors">Disetujui Koordinator</button>
                <button class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-[11px] md:text-xs font-semibold whitespace-nowrap transition-colors">Disetujui KPS</button>
                <button class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-[11px] md:text-xs font-semibold whitespace-nowrap transition-colors">Selesai</button>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="text-slate-400 font-bold text-[10px] uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 md:px-6 py-3 w-10"><input type="checkbox" class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue cursor-pointer"></th>
                            <th class="px-2 py-3 w-10 text-center">#</th>
                            <th class="px-4 py-3">MAHASISWA</th>
                            <th class="px-4 py-3">JENIS & PERUSAHAAN</th>
                            <th class="px-4 py-3">TGL DAFTAR</th>
                            <th class="px-4 py-3">DOKUMEN</th>
                            <th class="px-5 md:px-6 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        @foreach($queue as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 md:px-6 py-4"><input type="checkbox" class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue cursor-pointer"></td>
                                <td class="px-2 py-4">
                                    <span class="w-5 h-5 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-[10px] font-bold">{{ $item['id'] }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-full {{ $item['bg'] }} flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ $item['initial'] }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-[12px] md:text-[13px]">{{ $item['name'] }}</div>
                                            <div class="text-[10px] md:text-[11px] text-slate-500 font-medium">{{ $item['nim'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $item['typeBg'] }} mb-1">
                                        {{ $item['type'] }}
                                    </span>
                                    <div class="text-[11px] md:text-[12px] font-semibold text-slate-700">{{ $item['company'] }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-[11px] md:text-[12px] font-bold text-slate-700">{{ $item['date'] }}</div>
                                    <div class="text-[10px] md:text-[11px] font-semibold {{ $item['daysColor'] }}">{{ $item['days'] }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-1.5">
                                        @foreach($item['docs'] as $doc)
                                            @if($doc == 'ok')
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-4 text-center">
                                    <a href="{{ route('koordinator.show', $item['id']) }}" class="inline-block px-4 py-1.5 bg-brand-blue text-white rounded-lg text-[10px] md:text-[11px] font-bold hover:bg-blue-800 transition-colors shadow-sm">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-5 md:px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-[10px] md:text-[11px] text-slate-500 font-medium">
                <span>Menampilkan 5 dari 8 antrian • <strong>Legenda: <span class="text-emerald-500 ml-1">✓ Lengkap</span> <span class="text-rose-500 ml-1">✗ Kurang</span></strong></span>
                <span>Dok: Surat • KHS • CV • Proposal</span>
            </div>

        
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col border-t-4 border-t-brand-yellow h-full">
            <div class="px-5 md:px-6 py-4 md:py-5 bg-white border-b border-slate-100">
                <h2 class="text-sm md:text-[15px] font-bold text-slate-800">Statistik Jenis Magang</h2>
                <p class="text-[11px] text-slate-500 mt-0.5 md:mt-1">Semester Genap 2023/2024</p>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center items-center relative min-h-[300px]">
                <div class="w-full max-w-[180px] md:max-w-[220px] aspect-square relative flex items-center justify-center">
                    <canvas id="magangTypeChart"></canvas>
                    <!-- Center overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-1">
                        <span class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight">147</span>
                        <span class="text-[10px] md:text-[11px] font-bold text-slate-500">Total</span>
                    </div>
                </div>
            </div>
    </div>


</div>

<!-- Chart.js Init -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('magangTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Magang Wajib', 'Magang Pilihan', 'Magang Mandiri'],
                datasets: [{
                    data: [{{ $chartData[0] }}, {{ $chartData[1] }}, {{ $chartData[2] }}],
                    backgroundColor: [
                        '#F5A623', // yellow
                        '#0B4A8F', // blue
                        '#10B981'  // emerald
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 12, family: 'Inter', weight: 'bold' },
                        bodyFont: { size: 12, family: 'Inter' },
                    }
                }
            }
        });
    });
</script>
@endsection
