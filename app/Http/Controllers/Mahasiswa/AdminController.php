<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendaftaranMagang;
use App\Models\MitraPerusahaan;
use App\Models\Logbook;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            ['title' => 'Total User Aktif', 'value' => User::count(), 'trend' => '+14 bulan ini', 'trend_dir' => 'up', 'icon' => 'users', 'color' => 'sky'],
            ['title' => 'Mahasiswa Aktif', 'value' => User::where('role', 'mahasiswa')->count(), 'trend' => '+8 minggu ini', 'trend_dir' => 'up', 'icon' => 'academic', 'color' => 'indigo'],
            ['title' => 'Mitra Terdaftar', 'value' => MitraPerusahaan::count(), 'trend' => '+3 bulan ini', 'trend_dir' => 'up', 'icon' => 'briefcase', 'color' => 'amber'],
            ['title' => 'Pendaftaran Baru', 'value' => PendaftaranMagang::where('status', PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI)->count(), 'trend' => 'Menunggu verifikasi', 'trend_dir' => 'wait', 'icon' => 'document', 'color' => 'emerald'],
            ['title' => 'Logbook Hari Ini', 'value' => Logbook::whereDate('tanggal', today())->count(), 'trend' => 'Normal', 'trend_dir' => 'neutral', 'icon' => 'book', 'color' => 'slate'],
            ['title' => 'Nilai Belum Proses', 'value' => PendaftaranMagang::where('status', PendaftaranMagang::STATUS_SELESAI)->count(), 'trend' => 'Perhatian diperlukan', 'trend_dir' => 'down', 'icon' => 'exclamation', 'color' => 'rose'],
        ];

        $parameter = [
            'id' => 1,
            'industri' => 40,
            'dosen' => 35,
            'penguji' => 25,
            'total' => 100,
            'last_updated' => '1 Juni 2025'
        ];

        $users = User::latest()->take(5)->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => ucfirst($u->role),
                'status' => true,
                'initial' => strtoupper(substr($u->name, 0, 2)),
                'role_color' => $u->role == 'admin' ? 'rose' : ($u->role == 'mahasiswa' ? 'sky' : 'emerald')
            ];
        });

        return view('admin.dashboard.index', compact('stats', 'parameter', 'users'));
    }

    public function updateParameter(Request $request, $id)
    {
        $request->validate([
            'industri' => 'required|numeric|min:0|max:100',
            'dosen' => 'required|numeric|min:0|max:100',
            'penguji' => 'required|numeric|min:0|max:100',
        ]);

        $total = $request->industri + $request->dosen + $request->penguji;
        
        if ($total !== 100) {
            return back()->with('error', 'Total bobot nilai harus 100%. Saat ini: ' . $total . '%');
        }

        return back()->with('success', 'Parameter penilaian berhasil diperbarui.');
    }
}
