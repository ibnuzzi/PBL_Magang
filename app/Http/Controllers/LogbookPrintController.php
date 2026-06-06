<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class LogbookPrintController extends Controller
{
    public function print(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'mahasiswa_id' => $user?->role !== 'mahasiswa' ? 'required|exists:users,id' : 'nullable|exists:users,id',
        ]);

        $mahasiswaId = $user->role === 'mahasiswa' ? $user->id : $request->input('mahasiswa_id');

        // Security check: non-admin roles (dosen/koordinator/kps/kajur) can only print for students they supervise.
        if (in_array($user->role, ['dosen', 'koordinator', 'kps', 'kajur'])) {
            $hasAccess = \App\Models\PelaksanaanMagang::whereHas('pendaftaran', function ($q) use ($user, $mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                  ->where('dosen_pembimbing_id', $user->id);
            })->exists();

            if (!$hasAccess) {
                abort(403, 'Anda tidak memiliki akses untuk mencetak logbook mahasiswa ini.');
            }
        }

        $mahasiswa = User::with('programStudi')->findOrFail($mahasiswaId);

        $logbooks = Logbook::whereHas('pelaksanaan.pendaftaran', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->whereBetween('tanggal', [$request->input('tanggal_mulai'), $request->input('tanggal_selesai')])
            ->orderBy('tanggal', 'asc')
            ->get();

        $pelaksanaan = \App\Models\PelaksanaanMagang::whereHas('pendaftaran', function ($q) use ($mahasiswaId) {
            $q->where('mahasiswa_id', $mahasiswaId);
        })->with('pendaftaran.mitra', 'pendaftaran.dosenPembimbing')->first();

        return view('logbook.print', [
            'mahasiswa' => $mahasiswa,
            'logbooks' => $logbooks,
            'tanggal_mulai' => \Carbon\Carbon::parse($request->input('tanggal_mulai')),
            'tanggal_selesai' => \Carbon\Carbon::parse($request->input('tanggal_selesai')),
            'pelaksanaan' => $pelaksanaan,
        ]);
    }
}
