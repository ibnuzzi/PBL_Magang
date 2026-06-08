<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PegawaiResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Program Studi as dependency
        \App\Models\ProgramStudi::create([
            'kode' => 'TI',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'D4',
        ]);
    }

    public function test_admin_can_access_pegawai_resource()
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/pegawai');

        $response->assertStatus(200);
    }

    public function test_dosen_cannot_access_pegawai_resource()
    {
        $dosen = User::create([
            'name' => 'Andi Dosen',
            'email' => 'dosen@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);

        // Dosen panel is at /dosen/pegawai
        $response = $this->actingAs($dosen)
            ->get('/dosen/pegawai');

        $response->assertStatus(403);
    }

    public function test_mahasiswa_cannot_access_pegawai_resource()
    {
        $mahasiswa = User::create([
            'name' => 'Ahmad Student',
            'email' => 'ahmad@student.polinema.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'is_active' => true,
        ]);

        // Trying to access via any panel
        $response = $this->actingAs($mahasiswa)
            ->get('/mahasiswa/pegawai');

        $response->assertStatus(403); // Accessing unauthorized resource returns 403 Forbidden
    }

    public function test_koordinator_cannot_access_pegawai_resource()
    {
        $koordinator = User::create([
            'name' => 'Budi Koordinator',
            'email' => 'koordinator@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'koordinator',
            'is_active' => true,
        ]);

        // Koordinator panel is at /koordinator/pegawai
        $response = $this->actingAs($koordinator)
            ->get('/koordinator/pegawai');

        $response->assertStatus(403);
    }
}
