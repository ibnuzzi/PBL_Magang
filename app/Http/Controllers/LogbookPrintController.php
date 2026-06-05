<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class LogbookPrintController extends Controller
{
    public function print(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'mahasiswa_id' => 'nullable|exists:users,id',
        ]);

        $user = auth()->user();
        $mahasiswaId = $user->id;

        // Admin can specify a student
        if (in_array($user->role, ['admin', 'koordinator', 'dosen']) && $request->filled('mahasiswa_id')) {
            $mahasiswaId = $request->input('mahasiswa_id');
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
