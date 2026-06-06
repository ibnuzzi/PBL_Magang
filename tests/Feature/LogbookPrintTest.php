<?php

namespace Tests\Feature;

use App\Models\MitraPerusahaan;
use App\Models\PelaksanaanMagang;
use App\Models\PendaftaranMagang;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookPrintTest extends TestCase
{
    use RefreshDatabase;

    protected ProgramStudi $prodi;
    protected User $mahasiswa1;
    protected User $mahasiswa2;
    protected User $dosen1;
    protected User $dosen2;
    protected User $admin;
    protected PelaksanaanMagang $pelaksanaan1;
    protected PelaksanaanMagang $pelaksanaan2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prodi = ProgramStudi::create([
            'kode' => 'TI',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'D4',
        ]);

        $this->mahasiswa1 = User::create([
            'name' => 'Ahmad Student',
            'email' => 'ahmad@student.polinema.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'program_studi_id' => $this->prodi->id,
            'is_active' => true,
        ]);

        $this->mahasiswa2 = User::create([
            'name' => 'Budi Student',
            'email' => 'budi@student.polinema.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'program_studi_id' => $this->prodi->id,
            'is_active' => true,
        ]);

        $this->dosen1 = User::create([
            'name' => 'Dosen Satu',
            'email' => 'dosen1@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);

        $this->dosen2 = User::create([
            'name' => 'Dosen Dua',
            'email' => 'dosen2@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'admin',
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

        $pendaftaran1 = PendaftaranMagang::create([
            'mahasiswa_id' => $this->mahasiswa1->id,
            'mitra_id' => $mitra->id,
            'dosen_pembimbing_id' => $this->dosen1->id,
            'jenis_magang' => 'pilihan',
            'status' => 'berjalan',
        ]);

        $pendaftaran2 = PendaftaranMagang::create([
            'mahasiswa_id' => $this->mahasiswa2->id,
            'mitra_id' => $mitra->id,
            'dosen_pembimbing_id' => $this->dosen2->id,
            'jenis_magang' => 'pilihan',
            'status' => 'berjalan',
        ]);

        $this->pelaksanaan1 = PelaksanaanMagang::create([
            'pendaftaran_id' => $pendaftaran1->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'nama_supervisor' => 'Suparman',
            'jabatan_supervisor' => 'CTO',
            'no_hp_supervisor' => '08123456789',
            'status' => 'berjalan',
        ]);

        $this->pelaksanaan2 = PelaksanaanMagang::create([
            'pendaftaran_id' => $pendaftaran2->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'nama_supervisor' => 'Suparmin',
            'jabatan_supervisor' => 'CEO',
            'no_hp_supervisor' => '08123456780',
            'status' => 'berjalan',
        ]);
    }

    public function test_guest_cannot_print_logbook()
    {
        $response = $this->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_student_can_print_their_own_logbook()
    {
        $response = $this->actingAs($this->mahasiswa1)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Student');
        $response->assertSee('Laporan Kegiatan Magang Harian (Logbook)');
    }

    public function test_student_is_forced_to_print_own_logbook_even_if_requesting_others()
    {
        $response = $this->actingAs($this->mahasiswa1)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'mahasiswa_id' => $this->mahasiswa2->id, // Attempt to print mahasiswa2's
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Student');
        $response->assertDontSee('Budi Student');
    }

    public function test_dosen_can_print_their_supervised_students_logbook()
    {
        $response = $this->actingAs($this->dosen1)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'mahasiswa_id' => $this->mahasiswa1->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Student');
        $response->assertSee('Dosen Satu');
    }

    public function test_dosen_cannot_print_unsupervised_students_logbook()
    {
        $response = $this->actingAs($this->dosen1)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'mahasiswa_id' => $this->mahasiswa2->id, // supervised by dosen2, not dosen1
        ]));

        $response->assertStatus(403);
    }

    public function test_admin_can_print_any_students_logbook()
    {
        $response1 = $this->actingAs($this->admin)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'mahasiswa_id' => $this->mahasiswa1->id,
        ]));

        $response1->assertStatus(200);
        $response1->assertSee('Ahmad Student');

        $response2 = $this->actingAs($this->admin)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'mahasiswa_id' => $this->mahasiswa2->id,
        ]));

        $response2->assertStatus(200);
        $response2->assertSee('Budi Student');
    }

    public function test_non_student_roles_validation_requires_mahasiswa_id()
    {
        $response = $this->actingAs($this->admin)->get(route('mahasiswa.logbook.print', [
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            // missing mahasiswa_id
        ]));

        $response->assertSessionHasErrors('mahasiswa_id');
    }
}
