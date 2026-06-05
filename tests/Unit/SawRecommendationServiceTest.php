<?php

namespace Tests\Unit;

use App\Models\LowonganMagang;
use App\Models\MitraPerusahaan;
use App\Models\User;
use App\Services\SawRecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SawRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_recommendations_with_saw_ranks_correctly(): void
    {
        // 1. Create a mock admin user for lowongan creator
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admintest@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Create mock mitra companies
        $mitra1 = MitraPerusahaan::create([
            'nama' => 'PT Telkom',
            'alamat' => 'Bandung',
            'bidang_usaha' => 'IT',
            'nama_pic' => 'PIC 1',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0812',
            'email_pic' => 'pic1@telkom.com',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $mitra2 = MitraPerusahaan::create([
            'nama' => 'PT Pertamina',
            'alamat' => 'Jakarta',
            'bidang_usaha' => 'Energi',
            'nama_pic' => 'PIC 2',
            'jabatan_pic' => 'HR',
            'no_hp_pic' => '0813',
            'email_pic' => 'pic2@pertamina.com',
            'status_verifikasi' => 'terverifikasi',
        ]);

        // 3. Create mock vacancies
        // Lowongan 1 requires Laravel & PHP
        $v1 = LowonganMagang::create([
            'mitra_id' => $mitra1->id,
            'pembuat_id' => $admin->id,
            'judul' => 'Laravel Developer Intern',
            'deskripsi' => 'We need an intern experienced in Laravel, PHP, and MySQL database development.',
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'syarat_ipk' => 3.00,
            'syarat_semester' => 5,
            'tanggal_buka' => '2026-01-01',
            'tanggal_tutup' => '2026-12-31',
            'is_published' => true,
        ]);

        // Lowongan 2 requires Python & Data Science
        $v2 = LowonganMagang::create([
            'mitra_id' => $mitra2->id,
            'pembuat_id' => $admin->id,
            'judul' => 'Python Data Analyst',
            'deskripsi' => 'Looking for an intern skilled in Python, data science, machine learning, and pandas.',
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'syarat_ipk' => 3.50,
            'syarat_semester' => 5,
            'tanggal_buka' => '2026-01-01',
            'tanggal_tutup' => '2026-12-31',
            'is_published' => true,
        ]);

        // 4. Create mock student
        $student = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@student.polinema.ac.id',
            'nim' => '2241760001',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'ipk' => 3.60,
            'semester' => 6,
            'skills' => 'laravel, php, mysql, javascript, git',
            'pengalaman' => 'built ecommerce website with Laravel and MySQL',
            'is_active' => true,
        ]);

        // 5. Calculate SAW Recommendations
        $service = new SawRecommendationService();
        $weights = ['cv' => 45, 'portfolio' => 45, 'ipk' => 10];
        $results = $service->calculateRecommendations($student, $weights);

        // 6. Assertions
        $this->assertNotEmpty($results);
        $this->assertCount(2, $results);

        // The student has strong Laravel skills, so Lowongan 1 should rank higher than Lowongan 2
        $this->assertEquals('Laravel Developer Intern', $results[0]['vacancy']->judul);
        $this->assertEquals('Python Data Analyst', $results[1]['vacancy']->judul);

        // Verification of scores structure
        $this->assertArrayHasKey('saw_score', $results[0]);
        $this->assertArrayHasKey('score_cv', $results[0]);
        $this->assertArrayHasKey('score_portfolio', $results[0]);
        $this->assertArrayHasKey('score_ipk', $results[0]);
    }
}
