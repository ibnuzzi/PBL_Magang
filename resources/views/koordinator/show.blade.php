@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-10 space-y-6">
    
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('koordinator.index') }}" class="p-2 bg-white rounded-lg border border-slate-200 text-slate-500 hover:text-brand-blue hover:border-brand-blue transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Review Pendaftaran Magang</h1>
            <p class="text-sm text-slate-500 mt-0.5">Tinjau kelengkapan dokumen dan berikan keputusan verifikasi.</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col border-t-4 border-t-brand-blue">
        <div class="p-6 sm:p-8 flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full {{ $item['bg'] }} flex items-center justify-center font-bold text-2xl shrink-0">
                    {{ $item['initial'] }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $item['name'] }}</h2>
                    <p class="text-sm font-medium text-slate-500 mt-1 mb-3">NIM: {{ $item['nim'] }}</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold {{ $item['typeBg'] }}">
                        Magang {{ $item['type'] }}
                    </span>
                </div>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Perusahaan Tujuan</p>
                <p class="text-sm font-bold text-slate-800">{{ $item['company'] }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">Tanggal Daftar: {{ $item['date'] }}</p>
                
                @if($item['status'] == 'pending' || $item['status'] == 'menunggu')
                    <span class="inline-flex mt-3 items-center px-2.5 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700">Menunggu Verifikasi</span>
                @elseif($item['status'] == 'approved' || $item['status'] == 'disetujui')
                    <span class="inline-flex mt-3 items-center px-2.5 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700">Disetujui</span>
                @else
                    <span class="inline-flex mt-3 items-center px-2.5 py-1 rounded text-xs font-bold bg-rose-100 text-rose-700">Ditolak</span>
                @endif
            </div>
        </div>

        <div class="border-t border-slate-100 p-6 sm:p-8 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Kelengkapan Dokumen</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $docNames = ['Surat Pengantar', 'KHS Terakhir', 'Curriculum Vitae', 'Proposal Magang'];
                @endphp
                @foreach($item['docs'] as $index => $doc)
                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-col items-center text-center gap-3">
                        @if($doc == 'ok')
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $docNames[$index] }}</p>
                                <p class="text-[10px] font-bold text-emerald-600 mt-1">Lengkap</p>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $docNames[$index] }}</p>
                                <p class="text-[10px] font-bold text-rose-600 mt-1">Belum Ada</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="p-6 sm:p-8 border-t border-slate-100 bg-white">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Tindakan Verifikasi</h3>
            <p class="text-sm text-slate-600 mb-6">Apakah Anda menyetujui pendaftaran magang mahasiswa ini? Keputusan yang telah diambil akan diteruskan ke mahasiswa terkait.</p>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <form action="{{ route('koordinator.verifikasi.update', $item['id']) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" onclick="return confirm('Yakin ingin menyetujui pendaftaran ini?')" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Setujui Pendaftaran
                    </button>
                </form>
                
                <form action="{{ route('koordinator.verifikasi.update', $item['id']) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" onclick="return confirm('Yakin ingin menolak pendaftaran ini?')" class="w-full py-3 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
