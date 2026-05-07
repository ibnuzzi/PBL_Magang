@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Admin</h1>
                <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-widest">
                    Sistem Informasi Magang JTI Polinema — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Online
                </span>
                <button
                    class="flex items-center gap-2 px-3 py-1.5 bg-brand-blue text-white text-[11px] font-bold rounded-lg hover:bg-blue-900 transition-all shadow-sm border border-blue-950">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($stats as $stat)
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all group">
                    <div
                        class="p-2 w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 mb-3 group-hover:scale-110 transition-transform">
                        @if($stat['icon'] == 'users')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        @elseif($stat['icon'] == 'academic')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                </path>
                            </svg>
                        @elseif($stat['icon'] == 'briefcase')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        @elseif($stat['icon'] == 'document')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        @elseif($stat['icon'] == 'book')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <h4 class="text-2xl font-black text-slate-800">{{ $stat['value'] }}</h4>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">{{ $stat['title'] }}</p>
                    <div class="mt-2 flex items-center gap-1">
                        @if($stat['trend_dir'] == 'up')
                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-emerald-600">{{ $stat['trend'] }}</span>
                        @elseif($stat['trend_dir'] == 'wait')
                            <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-amber-600">{{ $stat['trend'] }}</span>
                        @elseif($stat['trend_dir'] == 'down')
                            <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span class="text-[10px] font-bold text-rose-600">{{ $stat['trend'] }}</span>
                        @else
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-slate-500">{{ $stat['trend'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Alert Section -->
        <div
            class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm border border-amber-100">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-full bg-amber-200 flex items-center justify-center text-amber-700 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-amber-900">17 Nilai Belum Diproses — Perhatian Diperlukan</h5>
                    <p class="text-xs text-amber-800 leading-relaxed max-w-2xl">
                        Terdapat mahasiswa magang yang telah selesai masa magang namun belum mendapatkan nilai akhir dari
                        dosen/penguji. Segera koordinasikan.
                    </p>
                </div>
            </div>
            <button
                class="px-5 py-2.5 bg-[#F5A623] hover:bg-[#E2971C] text-[#63430a] text-xs font-black rounded-xl transition-all shadow-md">
                Tinjau Sekarang
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Parameter Penilaian -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm h-full flex flex-col overflow-hidden">
                    <div class="p-6 pb-2">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-600 rounded-lg border border-amber-100">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider">Fitur Kunci</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none">
                                    Total:</p>
                                <p class="text-xl font-black text-emerald-500">100%</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Parameter Penilaian</h3>
                        <p class="text-xs text-slate-400 mt-1 mb-6">Bobot komponen nilai akhir magang</p>

                        <form action="{{ route('admin.dashboard.parameter.update', $parameter['id']) }}" method="POST"
                            class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                @foreach(['industri' => ['Nilai Industri', 'supervisor perusahaan', 'blue'], 'dosen' => ['Nilai Dosen', 'dosen pembimbing', 'emerald'], 'penguji' => ['Nilai Penguji', 'dosen penguji sidang', 'fuchsia']] as $key => $info)
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-end">
                                            <div>
                                                <h5 class="text-xs font-black text-slate-700 leading-none">{{ $info[0] }}</h5>
                                                <p class="text-[10px] text-slate-400 mt-1 italic">Dari {{ $info[1] }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-{{ $info[2] }}-500 rounded-full"
                                                        style="width: {{ $parameter[$key] }}%"></div>
                                                </div>
                                                <input type="number" name="{{ $key }}" value="{{ $parameter[$key] }}"
                                                    class="w-14 h-9 text-center text-sm font-bold bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                                <span class="text-xs font-bold text-slate-400">%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-4 space-y-4">
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden flex">
                                    <div class="h-full bg-blue-500" style="width: {{ $parameter['industri'] }}%"></div>
                                    <div class="h-full bg-emerald-500" style="width: {{ $parameter['dosen'] }}%"></div>
                                    <div class="h-full bg-fuchsia-500" style="width: {{ $parameter['penguji'] }}%"></div>
                                </div>
                                <div
                                    class="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    <span>Total Bobot</span>
                                    <span class="text-brand-blue">100%</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 bg-[#F5A623] hover:bg-[#E2971C] text-[#63430a] text-xs font-black rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <p class="text-[9px] text-center text-slate-400 font-medium">Terakhir diubah:
                                {{ $parameter['last_updated'] }} oleh Admin</p>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Manajemen User -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm h-full flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight">Manajemen User</h3>
                                <p class="text-xs text-slate-400 mt-1">348 user terdaftar — tampil 5 terbaru</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    class="px-4 py-2 bg-brand-blue hover:bg-blue-900 text-white text-[11px] font-black rounded-lg transition-all shadow-sm flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah User
                                </button>
                                <button
                                    class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-[#0B4A8F] text-[11px] font-black rounded-lg transition-all border border-slate-200 flex items-center gap-2">
                                    Lihat Semua
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Nama / Email</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Role</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-9 h-9 rounded-full bg-{{ $user['role_color'] }}-50 text-{{ $user['role_color'] }}-600 flex items-center justify-center text-xs font-black border border-{{ $user['role_color'] }}-100 shadow-sm flex-shrink-0">
                                                    {{ $user['initial'] }}
                                                </div>
                                                <div class="min-w-0">
                                                    <h6 class="text-xs font-black text-slate-700 truncate leading-none">
                                                        {{ $user['name'] }}</h6>
                                                    <p class="text-[10px] text-slate-400 mt-1 truncate">{{ $user['email'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex px-2 py-0.5 bg-{{ $user['role_color'] }}-50 text-{{ $user['role_color'] }}-600 text-[10px] font-bold rounded border border-{{ $user['role_color'] }}-100">
                                                {{ $user['role'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2"
                                                x-data="{ enabled: {{ $user['status'] ? 'true' : 'false' }} }">
                                                <button @click="enabled = !enabled"
                                                    :class="enabled ? 'bg-emerald-500' : 'bg-slate-200'"
                                                    class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors focus:outline-none">
                                                    <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                                                        class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform"></span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button
                                                    class="text-[10px] font-black text-brand-blue hover:underline px-2 py-1 rounded hover:bg-blue-50 transition-all">Edit</button>
                                                <button
                                                    class="text-[10px] font-black text-rose-500 hover:underline px-2 py-1 rounded hover:bg-rose-50 transition-all">Nonaktifkan</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection