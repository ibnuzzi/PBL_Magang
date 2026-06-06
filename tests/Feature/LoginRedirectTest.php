<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Program Studi as dependency
        $prodi = \App\Models\ProgramStudi::create([
            'kode' => 'TI',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'D4',
        ]);
    }

    public function test_login_page_loads_successfully()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Portal Magang JTI');
    }

    public function test_mahasiswa_redirects_to_mahasiswa_panel()
    {
        $mahasiswa = User::create([
            'name' => 'Ahmad Student',
            'email' => 'ahmad@student.polinema.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'ahmad@student.polinema.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/mahasiswa');
    }

    public function test_koordinator_redirects_to_koordinator_panel()
    {
        $koordinator = User::create([
            'name' => 'Budi Koordinator',
            'email' => 'koordinator@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'koordinator',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'koordinator@simagang.jti',
            'password' => 'password',
        ]);

        $response->assertRedirect('/koordinator');
    }

    public function test_dosen_redirects_to_dosen_panel()
    {
        $dosen = User::create([
            'name' => 'Andi Dosen',
            'email' => 'dosen@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'dosen@simagang.jti',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dosen');
    }

    public function test_wadir_redirects_to_wadir_panel()
    {
        $wadir = User::create([
            'name' => 'Supriyanto Wadir',
            'email' => 'wadir1@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'wadir1',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'wadir1@simagang.jti',
            'password' => 'password',
        ]);

        $response->assertRedirect('/wadir');
    }

    public function test_inactive_user_cannot_login()
    {
        $inactive = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@simagang.jti',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(\Illuminate\Support\Facades\Auth::check());
    }

    public function test_mahasiswa_cannot_access_koordinator_panel()
    {
        $mahasiswa = User::create([
            'name' => 'Ahmad Student',
            'email' => 'ahmad@student.polinema.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'semester' => 6,
            'is_active' => true,
        ]);

        $response = $this->actingAs($mahasiswa)
            ->get('/koordinator');

        $response->assertStatus(403);
    }

    public function test_admin_redirects_to_admin_panel()
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@simagang.jti',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }
}
