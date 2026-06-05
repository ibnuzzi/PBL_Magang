<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\LogbookSupervisorToken;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogbookApprovalController extends Controller
{
    protected NotifikasiService $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function show(string $token)
    {
        $tokenRecord = LogbookSupervisorToken::where('token', $token)
            ->where('is_used', false)
            ->where('expired_at', '>', now())
            ->with(['logbook.pelaksanaan.pendaftaran.mahasiswa', 'logbook.pelaksanaan.pendaftaran.mitra'])
            ->first();

        if (!$tokenRecord) {
            return view('logbook.approve_error', [
                'message' => 'Tautan persetujuan tidak valid, sudah digunakan, atau telah kedaluwarsa (berlaku 7 hari sejak dibuat).'
            ]);
        }

        return view('logbook.approve', [
            'token' => $tokenRecord,
            'logbook' => $tokenRecord->logbook,
        ]);
    }

    public function process(Request $request, string $token)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        $tokenRecord = LogbookSupervisorToken::where('token', $token)
            ->where('is_used', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            return back()->with('error', 'Token persetujuan tidak valid atau kedaluwarsa.');
        }

        $logbook = $tokenRecord->logbook;
        $action = $request->input('action');
        $catatan = $request->input('catatan');

        DB::transaction(function () use ($tokenRecord, $logbook, $action, $catatan) {
            // Update token
            $tokenRecord->update(['is_used' => true]);

            // Update logbook
            $newStatus = $action === 'approve' ? 'disetujui' : 'ditolak';
            $logbook->update([
                'status_supervisor' => $newStatus,
                'submitted_at' => now(),
            ]);

            // Notify Dosen Pembimbing
            $dosen = $logbook->pelaksanaan?->pendaftaran?->dosenPembimbing;
            $mahasiswa = $logbook->pelaksanaan?->pendaftaran?->mahasiswa;
            
            if ($dosen && $mahasiswa) {
                $statusLabel = $action === 'approve' ? 'DISETUJUI' : 'DITOLAK';
                $this->notifikasiService->kirim(
                    $dosen,
                    'logbook_supervisor_processed',
                    "Logbook Mahasiswa {$statusLabel}",
                    "Logbook {$mahasiswa->name} tanggal {$logbook->tanggal->format('d-m-Y')} telah {$newStatus} oleh supervisor." . ($catatan ? " Catatan: {$catatan}" : ""),
                    "/admin/logbooks"
                );
            }

            // Notify Mahasiswa
            if ($mahasiswa) {
                $statusLabel = $action === 'approve' ? 'disetujui' : 'ditolak';
                $this->notifikasiService->kirim(
                    $mahasiswa,
                    'logbook_supervisor_processed',
                    "Logbook Anda {$action} oleh Supervisor",
                    "Logbook harian Anda tanggal {$logbook->tanggal->format('d-m-Y')} telah {$statusLabel} oleh supervisor." . ($catatan ? " Catatan: {$catatan}" : ""),
                    "/admin/logbooks"
                );
            }
        });

        $message = $action === 'approve' 
            ? 'Terima kasih! Logbook harian mahasiswa berhasil disetujui.' 
            : 'Logbook harian mahasiswa berhasil ditolak.';

        return view('logbook.approve_success', [
            'message' => $message
        ]);
    }
}
