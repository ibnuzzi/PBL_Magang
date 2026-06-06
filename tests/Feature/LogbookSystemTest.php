<?php

namespace Tests\Feature;

use App\Models\LowonganMagang;
use App\Models\MitraPerusahaan;
use App\Models\PendaftaranMagang;
use App\Models\User;
use App\Services\PendaftaranService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $pembuat;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pembuat = User::create([
            'name' => 'Koordinator Magang',
            'email' => 'koordinator@test.com',
            'password' => bcrypt('password'),
            'role' => 'koordinator',
            'is_active' => true,
        ]);
    }

    public function test_cannot_register_if_has_active_pendaftaran()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $lowongan = LowonganMagang::create([
            'mitra_id' => $mitra->id,
            'pembuat_id' => $this->pembuat->id,
            'judul' => 'Lowongan Test',
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'is_published' => true,
        ]);

        // Create an active pendaftaran
        PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'menunggu_approval_koordinator',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Anda masih memiliki pendaftaran magang yang aktif. Harap selesaikan seleksi atau batalkan terlebih dahulu.');

        app(PendaftaranService::class)->createDraftPilihan($mahasiswa, $lowongan);
    }

    public function test_cannot_register_for_sks_internship_with_non_official_mitra()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitraNonResmi = MitraPerusahaan::create([
            'nama' => 'Mitra Biasa',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Biasa',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@biasa.com',
            'is_resmi_polinema' => false,
            'is_cti' => false,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $lowongan = LowonganMagang::create([
            'mitra_id' => $mitraNonResmi->id,
            'pembuat_id' => $this->pembuat->id,
            'judul' => 'Lowongan Pilihan Non Resmi',
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'is_published' => true,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Magang SKS (Pilihan & Wajib) hanya diperbolehkan pada mitra resmi Polinema atau CTI.');

        app(PendaftaranService::class)->createDraftPilihan($mahasiswa, $lowongan);
    }

    public function test_cannot_register_if_semester_does_not_match()
    {
        $mahasiswaS5 = User::create([
            'name' => 'Test Student S5',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 5,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $lowongan = LowonganMagang::create([
            'mitra_id' => $mitra->id,
            'pembuat_id' => $this->pembuat->id,
            'judul' => 'Lowongan Test',
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'tanggal_buka' => now()->subDay(),
            'tanggal_tutup' => now()->addDay(),
            'is_published' => true,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Pendaftaran magang hanya diperbolehkan untuk mahasiswa semester 6 atau 7.');

        app(PendaftaranService::class)->createDraftPilihan($mahasiswaS5, $lowongan);
    }

    public function test_logbook_supervisor_token_generation()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'berjalan',
        ]);

        $pelaksanaan = \App\Models\PelaksanaanMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->addMonths(3),
            'nama_supervisor' => 'Suparman',
            'jabatan_supervisor' => 'CTO',
            'no_hp_supervisor' => '08123456789',
        ]);

        $logbook = \App\Models\Logbook::create([
            'pelaksanaan_id' => $pelaksanaan->id,
            'tanggal' => now()->toDateString(),
            'minggu_ke' => 1,
            'hari_ke' => 1,
            'kegiatan' => 'Coding',
            'hasil' => 'Laravel App',
            'status_supervisor' => 'menunggu',
        ]);

        $token = $logbook->generateSupervisorToken('08123456789');

        $this->assertDatabaseHas('logbook_supervisor_tokens', [
            'logbook_id' => $logbook->id,
            'token' => $token->token,
            'no_hp_supervisor' => '08123456789',
            'is_used' => false,
        ]);
    }

    public function test_public_supervisor_approval()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'berjalan',
        ]);

        $pelaksanaan = \App\Models\PelaksanaanMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->addMonths(3),
            'nama_supervisor' => 'Suparman',
            'jabatan_supervisor' => 'CTO',
            'no_hp_supervisor' => '08123456789',
        ]);

        $logbook = \App\Models\Logbook::create([
            'pelaksanaan_id' => $pelaksanaan->id,
            'tanggal' => now()->toDateString(),
            'minggu_ke' => 1,
            'hari_ke' => 1,
            'kegiatan' => 'Coding',
            'hasil' => 'Laravel App',
            'status_supervisor' => 'menunggu',
        ]);

        $token = $logbook->generateSupervisorToken('08123456789');

        // Test display page
        $responseShow = $this->get(route('logbook.approve', ['token' => $token->token]));
        $responseShow->assertStatus(200);

        // Test process approve
        $responseProcess = $this->post(route('logbook.approve.process', ['token' => $token->token]), [
            'action' => 'approve',
            'catatan' => 'Kerjaan bagus!',
        ]);

        $responseProcess->assertStatus(200);
        $this->assertEquals('disetujui', $logbook->fresh()->status_supervisor);
        $this->assertTrue($token->fresh()->is_used);
    }

    public function test_student_can_see_published_letters_on_status_page()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'surat_pengantar_terbit',
        ]);

        $suratDiterbitkan = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'pengantar',
            'nomor_surat' => 'SURAT-PUBLISHED-123',
            'file_path' => 'surat-magang/test_published.pdf',
            'status' => 'diterbitkan',
            'diterbitkan_at' => now(),
        ]);

        $suratDraft = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'loa',
            'nomor_surat' => 'SURAT-DRAFT-123',
            'file_path' => 'surat-magang/test_draft.pdf',
            'status' => 'draft',
            'diterbitkan_at' => null,
        ]);

        $response = $this->actingAs($mahasiswa)
            ->get(route('filament.mahasiswa.pages.status-pendaftaran'));

        $response->assertStatus(200);
        $response->assertSee('SURAT-PUBLISHED-123');
        $response->assertDontSee('SURAT-DRAFT-123');
        $response->assertSee('Surat Pengantar Magang');
        $response->assertDontSee('Letter of Acceptance (LOA)');
    }

    public function test_pendaftaran_status_transitions_when_letters_are_published()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'disetujui_penuh',
        ]);

        // Publish a Surat Pengantar
        $suratPengantar = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'pengantar',
            'nomor_surat' => 'SURAT-P-111',
            'file_path' => 'surat-magang/test_p.pdf',
            'status' => 'diterbitkan',
        ]);

        $this->assertEquals(PendaftaranMagang::STATUS_SURAT_TERBIT, $pendaftaran->fresh()->status);
        $this->assertNotNull($suratPengantar->fresh()->diterbitkan_at);

        // Publish a LOA
        $suratLoa = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'loa',
            'nomor_surat' => 'SURAT-L-222',
            'file_path' => 'surat-magang/test_l.pdf',
            'status' => 'diterbitkan',
        ]);

        $this->assertEquals(PendaftaranMagang::STATUS_LOA, $pendaftaran->fresh()->status);
    }

    public function test_surat_download_security()
    {
        $mahasiswa1 = User::create([
            'name' => 'Student One',
            'email' => 'student1@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa1->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'surat_pengantar_terbit',
        ]);

        // Create a mock file in storage
        \Illuminate\Support\Facades\Storage::fake();
        \Illuminate\Support\Facades\Storage::disk()->put('surat-magang/test_p.pdf', 'dummy content');

        $suratPengantar = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'pengantar',
            'nomor_surat' => 'SURAT-P-111',
            'file_path' => 'surat-magang/test_p.pdf',
            'status' => 'diterbitkan',
        ]);

        $suratDraft = \App\Models\SuratMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_surat' => 'loa',
            'nomor_surat' => 'SURAT-D-222',
            'file_path' => 'surat-magang/test_d.pdf',
            'status' => 'draft',
        ]);

        // 1. Guest cannot access
        $response = $this->get(route('surat.download', $suratPengantar->id));
        $response->assertRedirect(route('login'));

        // 2. Student One can download their own published letter
        $response = $this->actingAs($mahasiswa1)->get(route('surat.download', $suratPengantar->id));
        $response->assertStatus(200);

        // 3. Student One cannot download their own draft letter
        $response = $this->actingAs($mahasiswa1)->get(route('surat.download', $suratDraft->id));
        $response->assertStatus(403);

        // 4. Student Two cannot download Student One's letter
        $response = $this->actingAs($mahasiswa2)->get(route('surat.download', $suratPengantar->id));
        $response->assertStatus(403);

        // 5. Admin can download
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $response = $this->actingAs($admin)->get(route('surat.download', $suratPengantar->id));
        $response->assertStatus(200);
    }

    public function test_pendaftaran_status_transitions_to_berjalan_when_pelaksanaan_starts()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => 'loa_diterima',
        ]);

        $pelaksanaan = \App\Models\PelaksanaanMagang::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'nama_supervisor' => 'Suparman',
            'jabatan_supervisor' => 'CTO',
            'no_hp_supervisor' => '08123456789',
            'status' => 'berjalan',
        ]);

        $this->assertEquals(PendaftaranMagang::STATUS_BERJALAN, $pendaftaran->fresh()->status);
    }

    public function test_pendaftaran_status_transitions_when_documents_are_approved_or_rejected()
    {
        $mahasiswa = User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'ipk' => 3.50,
            'is_active' => true,
        ]);

        $mitra = MitraPerusahaan::create([
            'nama' => 'Mitra Resmi',
            'alamat' => 'Alamat',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC Resmi',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812345678',
            'email_pic' => 'pic@resmi.com',
            'is_resmi_polinema' => true,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $pendaftaran = PendaftaranMagang::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mitra_id' => $mitra->id,
            'jenis_magang' => 'pilihan',
            'status' => PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $doc1 = \App\Models\DokumenPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => 'khs',
            'file_path' => 'dokumen-pendaftaran/khs.pdf',
            'status' => 'menunggu',
        ]);

        $doc2 = \App\Models\DokumenPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => 'cv',
            'file_path' => 'dokumen-pendaftaran/cv.pdf',
            'status' => 'menunggu',
        ]);

        // 1. Approve doc1. Registration should still be STATUS_MENUNGGU_VERIFIKASI.
        $doc1->update(['status' => 'disetujui']);
        $this->assertEquals(PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI, $pendaftaran->fresh()->status);

        // 2. Approve doc2. Since all uploaded docs are now approved, registration status should transition to STATUS_MENUNGGU_KOORDINATOR.
        $doc2->update(['status' => 'disetujui']);
        $this->assertEquals(PendaftaranMagang::STATUS_MENUNGGU_KOORDINATOR, $pendaftaran->fresh()->status);

        // 3. Reset to STATUS_MENUNGGU_VERIFIKASI and mark doc2 as ditolak (rejected). Status should transition to STATUS_DOKUMEN_KURANG.
        $pendaftaran->refresh();
        $pendaftaran->update(['status' => PendaftaranMagang::STATUS_MENUNGGU_VERIFIKASI]);
        $doc2->update(['status' => 'ditolak']);
        $this->assertEquals(PendaftaranMagang::STATUS_DOKUMEN_KURANG, $pendaftaran->fresh()->status);
    }
}
