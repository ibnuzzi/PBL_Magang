<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PendaftaranMagang;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reconcile existing statuses based on published letters
        $pendaftarans = PendaftaranMagang::with('surat')->get();
        foreach ($pendaftarans as $pendaftaran) {
            $hasPublishedLoa = $pendaftaran->surat->where('jenis_surat', 'loa')->where('status', 'diterbitkan')->isNotEmpty();
            $hasPublishedPengantar = $pendaftaran->surat->where('jenis_surat', 'pengantar')->where('status', 'diterbitkan')->isNotEmpty();

            $flow = PendaftaranMagang::statusFlow();
            $currentIndex = array_search($pendaftaran->status, $flow);

            if ($hasPublishedLoa) {
                $loaIndex = array_search(PendaftaranMagang::STATUS_LOA, $flow);
                if ($currentIndex !== false && $currentIndex < $loaIndex) {
                    $pendaftaran->update(['status' => PendaftaranMagang::STATUS_LOA]);
                }
            } elseif ($hasPublishedPengantar) {
                $pengantarIndex = array_search(PendaftaranMagang::STATUS_SURAT_TERBIT, $flow);
                if ($currentIndex !== false && $currentIndex < $pengantarIndex) {
                    $pendaftaran->update(['status' => PendaftaranMagang::STATUS_SURAT_TERBIT]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
