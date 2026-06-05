<?php

namespace App\Http\Controllers;

use App\Models\SuratMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratDownloadController extends Controller
{
    /**
     * Download or stream the published internship letter.
     */
    public function download(SuratMagang $suratMagang)
    {
        $user = Auth::user();

        // 1. Authorization checks
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Admin and Coordinator have full access
        if (in_array($user->role, ['admin', 'koordinator'])) {
            // Authorized
        } elseif ($user->role === 'mahasiswa') {
            // Student can only access their own pendaftaran letters and only if diterbitkan
            if ((int) $suratMagang->pendaftaran->mahasiswa_id !== (int) $user->id) {
                abort(403, 'You do not have permission to view this letter.');
            }

            if ($suratMagang->status !== 'diterbitkan') {
                abort(403, 'This letter has not been published yet.');
            }
        } else {
            abort(403, 'Unauthorized role.');
        }

        // 2. File existence check
        $disk = Storage::disk();
        if (!$disk->exists($suratMagang->file_path)) {
            abort(404, 'File not found on storage.');
        }

        // 3. Return streaming response (inline PDF display)
        return $disk->response($suratMagang->file_path);
    }
}
