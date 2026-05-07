<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        
        $pendaftaran = PendaftaranMagang::where('mahasiswa_id', $authUser->id)
            ->with(['lowongan.perusahaan'])
            ->latest()
            ->first();
        
        $user = [
            'name' => $authUser->name,
            'nim' => $authUser->nim ?? $authUser->email,
            'program_studi' => $authUser->programStudi->nama ?? 'Teknologi Informasi',
            'semester' => $authUser->semester ?? '-',
            'status' => $pendaftaran ? $pendaftaran->status_label : 'Belum Terdaftar',
            'company' => $pendaftaran->lowongan->perusahaan->nama_perusahaan ?? 'Belum ada instansi'
        ];

        $stats = [
            'total_logbook' => 0,
            'menunggu_acc' => 0,
            'hari_berjalan' => 0,
            'hari_target' => 120,
            'nilai_sementara' => 0,
            'grade' => '-'
        ];

        $approval_steps = [
            ['title' => 'Koordinator', 'status' => 'Menunggu', 'date' => '-', 'state' => 'pending'],
            ['title' => 'KPS', 'status' => 'Menunggu', 'date' => '-', 'state' => 'pending'],
            ['title' => 'Kajur', 'status' => 'Menunggu', 'date' => '-', 'state' => 'pending'],
            ['title' => 'Wadir 1', 'status' => 'Menunggu', 'date' => '-', 'state' => 'pending'],
        ];

        $logbooks = [];
        $dokumen = [];

        return view('mahasiswa.dashboard.index', compact('user', 'stats', 'pendaftaran', 'approval_steps', 'logbooks', 'dokumen'));
    }
}
