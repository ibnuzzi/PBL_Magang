@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Mobile Header greeting (visible only on small screens) -->
    <div class="sm:hidden mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Selamat Pagi, Dr. Rini</h1>
        <p class="text-sm text-slate-500">Overview sistem informasi magang</p>
    </div>

    <!-- Stats Cards (6 box) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
            $stats = [
                ['title' => 'Total Pendaftar', 'value' => '1,245', 'icon' => 'users', 'color' => 'sky', 'bg' => 'bg-sky-50', 'text' => 'text-sky-600', 'trend' => '+12%'],
                ['title' => 'Menunggu Verif', 'value' => '48', 'icon' => 'clock', 'color' => 'amber', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'trend' => 'Urgent'],
                ['title' => 'Disetujui', 'value' => '856', 'icon' => 'check', 'color' => 'emerald', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'trend' => '+18%'],
                ['title' => 'Ditolak', 'value' => '12', 'icon' => 'x', 'color' => 'rose', 'bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'trend' => '-2%'],
                ['title' => 'Sedang Magang', 'value' => '642', 'icon' => 'briefcase', 'color' => 'indigo', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'trend' => 'Aktif'],
                ['title' => 'Selesai Magang', 'value' => '214', 'icon' => 'academic', 'color' => 'slate', 'bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'trend' => 'Lulus'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ $stat['title'] }}</p>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $stat['value'] }}</h3>
                    </div>
                    <div class="p-2 rounded-lg {{ $stat['bg'] }} {{ $stat['text'] }} group-hover:scale-110 transition-transform">
                        @if($stat['icon'] == 'users')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @elseif($stat['icon'] == 'clock')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($stat['icon'] == 'check')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($stat['icon'] == 'x')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($stat['icon'] == 'briefcase')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @elseif($stat['icon'] == 'academic')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    @if(strpos($stat['trend'], '+') !== false)
                        <span class="text-emerald-600 font-bold bg-emerald-100 px-2 py-0.5 rounded-md mr-2">{{ $stat['trend'] }}</span>
                        <span class="text-slate-400 font-medium">Bulan ini</span>
                    @elseif(strpos($stat['trend'], '-') !== false)
                        <span class="text-rose-600 font-bold bg-rose-100 px-2 py-0.5 rounded-md mr-2">{{ $stat['trend'] }}</span>
                        <span class="text-slate-400 font-medium">Bulan ini</span>
                    @else
                        <span class="{{ $stat['text'] }} font-bold {{ $stat['bg'] }} px-2 py-0.5 rounded-md mr-2">{{ $stat['trend'] }}</span>
                        <span class="text-slate-400 font-medium">Status</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Content & Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Table Data Mahasiswa -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Antrian Verifikasi Pendaftaran</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Mahasiswa yang perlu direview dokumennya.</p>
                </div>
                <button class="text-sm text-sky-600 font-semibold hover:text-sky-700 bg-sky-50 px-3 py-1.5 rounded-lg hover:bg-sky-100 transition-colors">
                    Lihat Semua
                </button>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Mahasiswa</th>
                            <th class="px-6 py-4">Jenis & Perusahaan</th>
                            <th class="px-6 py-4">Tanggal Daftar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $queue = [
                                ['name' => 'Ahmad Fauzi', 'nim' => '19021001', 'type' => 'Magang Mandiri', 'company' => 'PT Telkom Indonesia', 'date' => '12 Okt 2023', 'status' => 'Lengkap', 'color' => 'emerald'],
                                ['name' => 'Siti Nurhaliza', 'nim' => '19021002', 'type' => 'Magang MBKM', 'company' => 'Gojek Tokopedia (GoTo)', 'date' => '11 Okt 2023', 'status' => 'Lengkap', 'color' => 'emerald'],
                                ['name' => 'Budi Santoso', 'nim' => '19021003', 'type' => 'Magang Mandiri', 'company' => 'Bank Mandiri', 'date' => '10 Okt 2023', 'status' => 'Menunggu Revisi', 'color' => 'amber'],
                                ['name' => 'Dewi Lestari', 'nim' => '19021004', 'type' => 'Magang MBKM', 'company' => 'Traveloka', 'date' => '09 Okt 2023', 'status' => 'Lengkap', 'color' => 'emerald'],
                                ['name' => 'Rizky Ramadhan', 'nim' => '19021005', 'type' => 'Magang Fakultas', 'company' => 'PT Pertamina', 'date' => '08 Okt 2023', 'status' => 'Belum Lengkap', 'color' => 'rose'],
                            ];
                        @endphp

                        @foreach($queue as $item)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm border border-slate-200">
                                            {{ substr($item['name'], 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $item['name'] }}</div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $item['nim'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-700">{{ $item['company'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $item['type'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $item['date'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-700 border border-{{ $item['color'] }}-200">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 hover:text-sky-600 hover:border-sky-300 transition-all shadow-sm">
                                        Review
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-white">
                <h2 class="text-lg font-bold text-slate-800">Statistik Jenis Magang</h2>
                <p class="text-xs text-slate-500 mt-0.5">Distribusi program magang aktif.</p>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center items-center relative">
                <div class="w-full max-w-[220px] aspect-square relative">
                    <canvas id="magangTypeChart"></canvas>
                    <!-- Center overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-black text-slate-800 tracking-tight">1.2K</span>
                        <span class="text-xs font-medium text-slate-500">Mahasiswa</span>
                    </div>
                </div>
                
                <!-- Legend -->
                <div class="mt-8 w-full space-y-3 px-2">
                    <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-sky-500 shadow-sm shadow-sky-200"></span>
                            <span class="text-slate-600 font-medium">Magang MBKM</span>
                        </div>
                        <span class="font-bold text-slate-800">55%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></span>
                            <span class="text-slate-600 font-medium">Magang Mandiri</span>
                        </div>
                        <span class="font-bold text-slate-800">30%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-sm shadow-emerald-200"></span>
                            <span class="text-slate-600 font-medium">Magang Fakultas</span>
                        </div>
                        <span class="font-bold text-slate-800">15%</span>
                    </div>
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
                labels: ['Magang MBKM', 'Magang Mandiri', 'Magang Fakultas'],
                datasets: [{
                    data: [55, 30, 15],
                    backgroundColor: [
                        '#0ea5e9', // sky-500
                        '#6366f1', // indigo-500
                        '#34d399'  // emerald-400
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '78%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                        bodyFont: { size: 13, family: 'Inter' },
                        displayColors: true,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
