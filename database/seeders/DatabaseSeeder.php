<?php

namespace Database\Seeders;

use App\Models\LowonganMagang;
use App\Models\MitraPerusahaan;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Program Studi ───────────────────────────────────────────────
        $d4ti = ProgramStudi::create([
            'kode' => 'TI',
            'nama' => 'Teknik Informatika',
            'jenjang' => 'D4',
        ]);

        $d4sib = ProgramStudi::create([
            'kode' => 'SIB',
            'nama' => 'Sistem Informasi Bisnis',
            'jenjang' => 'D4',
        ]);

        $d3mi = ProgramStudi::create([
            'kode' => 'MI',
            'nama' => 'Manajemen Informatika',
            'jenjang' => 'D3',
        ]);

        // ─── Admin User ──────────────────────────────────────────────────
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simagang.jti',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ─── Wadir 1 ─────────────────────────────────────────────────────
        User::create([
            'name' => 'Prof. Dr. Ir. Supriyanto, M.T.',
            'email' => 'wadir1@simagang.jti',
            'nip' => '196001011990031001',
            'password' => bcrypt('password'),
            'role' => 'wadir1',
            'is_active' => true,
        ]);

        // ─── Kajur ───────────────────────────────────────────────────────
        User::create([
            'name' => 'Ir. Eko Juniarto, M.T.',
            'email' => 'kajur@simagang.jti',
            'nip' => '196506021995121001',
            'password' => bcrypt('password'),
            'role' => 'kajur',
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        // ─── KPS ─────────────────────────────────────────────────────────
        User::create([
            'name' => 'Dr. Rawansyah, M.Kom.',
            'email' => 'kps@simagang.jti',
            'nip' => '198001012010121001',
            'password' => bcrypt('password'),
            'role' => 'kps',
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        // ─── Koordinator ─────────────────────────────────────────────────
        $koordinator = User::create([
            'name' => 'Budi Santoso, S.Kom., M.Kom.',
            'email' => 'koordinator@simagang.jti',
            'nip' => '199103152019031001',
            'password' => bcrypt('password'),
            'role' => 'koordinator',
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        // ─── Dosen Pembimbing ────────────────────────────────────────────
        User::create([
            'name' => 'Andi Prasetyo, S.Kom., M.T.',
            'email' => 'dosen1@simagang.jti',
            'nip' => '199205202020031001',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Sari Dewi, S.Kom., M.Cs.',
            'email' => 'dosen2@simagang.jti',
            'nip' => '199307252020032001',
            'password' => bcrypt('password'),
            'role' => 'dosen',
            'program_studi_id' => $d4sib->id,
            'is_active' => true,
        ]);

        // ─── Mahasiswa ───────────────────────────────────────────────────
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@student.polinema.ac.id',
            'nim' => '2241760001',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'angkatan' => '2022',
            'ipk' => 3.65,
            'semester' => 6,
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti@student.polinema.ac.id',
            'nim' => '2241760002',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'angkatan' => '2022',
            'ipk' => 3.45,
            'semester' => 6,
            'program_studi_id' => $d4ti->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@student.polinema.ac.id',
            'nim' => '2241760003',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'angkatan' => '2022',
            'ipk' => 3.20,
            'semester' => 6,
            'program_studi_id' => $d4sib->id,
            'is_active' => true,
        ]);

        // ─── Mitra Perusahaan ────────────────────────────────────────────
        $telkom = MitraPerusahaan::create([
            'nama' => 'PT Telkom Indonesia (Persero) Tbk',
            'alamat' => 'Jl. Japati No.1, Sadang Serang, Bandung, Jawa Barat 40133',
            'bidang_usaha' => 'Telekomunikasi & Digital',
            'nama_pic' => 'Rina Andriani',
            'jabatan_pic' => 'HR Manager',
            'no_hp_pic' => '081234567890',
            'email_pic' => 'rina@telkom.co.id',
            'is_resmi_polinema' => true,
            'is_cti' => false,
            'kuota_mahasiswa' => 15,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $traveloka = MitraPerusahaan::create([
            'nama' => 'PT Trinusa Travelindo (Traveloka)',
            'alamat' => 'Wisma 77 Tower 2, Jl. Letjen S. Parman, Jakarta Barat 11410',
            'bidang_usaha' => 'E-Commerce / Travel Tech',
            'nama_pic' => 'Denny Kurniawan',
            'jabatan_pic' => 'Talent Acquisition',
            'no_hp_pic' => '087812345678',
            'email_pic' => 'denny@traveloka.com',
            'is_resmi_polinema' => true,
            'is_cti' => false,
            'kuota_mahasiswa' => 10,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $cti = MitraPerusahaan::create([
            'nama' => 'PT Central Transformasi Indonesia (CTI)',
            'alamat' => 'Jl. Soekarno-Hatta No.9, Malang, Jawa Timur',
            'bidang_usaha' => 'Konsultan IT & Pengembangan Software',
            'nama_pic' => 'Agus Firmansyah',
            'jabatan_pic' => 'Project Manager',
            'no_hp_pic' => '085678901234',
            'email_pic' => 'agus@cti.co.id',
            'is_resmi_polinema' => true,
            'is_cti' => true,
            'kuota_mahasiswa' => 20,
            'status_verifikasi' => 'terverifikasi',
        ]);

        $gdp = MitraPerusahaan::create([
            'nama' => 'PT Gamatechno Indonesia',
            'alamat' => 'Jl. Cik Di Tiro No.34, Yogyakarta 55223',
            'bidang_usaha' => 'Software Development',
            'nama_pic' => 'Wulan Sari',
            'jabatan_pic' => 'HRD',
            'no_hp_pic' => '089912345678',
            'email_pic' => 'wulan@gamatechno.com',
            'is_resmi_polinema' => false,
            'is_cti' => false,
            'kuota_mahasiswa' => 5,
            'status_verifikasi' => 'terverifikasi',
        ]);

        MitraPerusahaan::create([
            'nama' => 'PT Solusi Digital Nusantara',
            'alamat' => 'Jl. Veteran No.10, Malang, Jawa Timur',
            'bidang_usaha' => 'Digital Agency',
            'nama_pic' => 'Hendra Wijaya',
            'jabatan_pic' => 'CEO',
            'no_hp_pic' => '081112223344',
            'email_pic' => 'hendra@solusidiginusa.id',
            'is_resmi_polinema' => false,
            'is_cti' => false,
            'kuota_mahasiswa' => 3,
            'status_verifikasi' => 'terverifikasi',
        ]);

        // ─── Lowongan Magang ─────────────────────────────────────────────
        LowonganMagang::create([
            'mitra_id' => $telkom->id,
            'pembuat_id' => $koordinator->id,
            'judul' => 'Magang Fullstack Developer - Telkom Digital 2025',
            'deskripsi' => "Bergabung dengan tim Digital Telkom untuk mengembangkan aplikasi berbasis web dan mobile.\n\nKualifikasi:\n- Menguasai Laravel / React / Vue\n- Memahami konsep RESTful API\n- Mampu bekerja dalam tim Agile/Scrum",
            'jenis_magang' => 'pilihan',
            'kuota' => 5,
            'kuota_terisi' => 0,
            'syarat_ipk' => 3.00,
            'syarat_semester' => 5,
            'syarat_prodi' => [$d4ti->id, $d4sib->id],
            'dokumen_required' => ['krs', 'transkip_nilai', 'cv', 'surat_lamaran', 'proposal_magang'],
            'tanggal_buka' => '2026-04-01',
            'tanggal_tutup' => '2026-05-31',
            'tanggal_mulai_magang' => '2026-07-01',
            'tanggal_selesai_magang' => '2026-12-31',
            'is_published' => true,
            'is_full' => false,
        ]);

        LowonganMagang::create([
            'mitra_id' => $traveloka->id,
            'pembuat_id' => $koordinator->id,
            'judul' => 'Magang Data Analyst - Traveloka 2025',
            'deskripsi' => "Posisi magang untuk menganalisis data bisnis menggunakan SQL, Python, dan tools visualisasi data.\n\nTugas Utama:\n- Analisis data booking & traffic\n- Membuat dashboard monitoring\n- Report harian & mingguan",
            'jenis_magang' => 'pilihan',
            'kuota' => 3,
            'kuota_terisi' => 0,
            'syarat_ipk' => 3.25,
            'syarat_semester' => 6,
            'syarat_prodi' => [$d4ti->id, $d4sib->id, $d3mi->id],
            'dokumen_required' => ['krs', 'transkip_nilai', 'cv', 'surat_lamaran'],
            'tanggal_buka' => '2026-04-15',
            'tanggal_tutup' => '2026-06-15',
            'tanggal_mulai_magang' => '2026-08-01',
            'tanggal_selesai_magang' => '2026-12-31',
            'is_published' => true,
            'is_full' => false,
        ]);

        LowonganMagang::create([
            'mitra_id' => $cti->id,
            'pembuat_id' => $koordinator->id,
            'judul' => 'Magang Wajib CTI - Batch 1 2025',
            'deskripsi' => "Program magang wajib kerjasama JTI Polinema dengan CTI.\nMahasiswa akan ditempatkan di project-project pengembangan software selama 6 bulan.",
            'jenis_magang' => 'wajib',
            'kuota' => 20,
            'kuota_terisi' => 0,
            'syarat_ipk' => 0,
            'syarat_semester' => 5,
            'syarat_prodi' => [$d4ti->id],
            'dokumen_required' => ['krs', 'transkip_nilai', 'cv'],
            'tanggal_buka' => '2026-03-01',
            'tanggal_tutup' => '2026-06-30',
            'tanggal_mulai_magang' => '2026-07-01',
            'tanggal_selesai_magang' => '2026-12-31',
            'is_published' => true,
            'is_full' => false,
        ]);

        LowonganMagang::create([
            'mitra_id' => $gdp->id,
            'pembuat_id' => $koordinator->id,
            'judul' => 'Magang UI/UX Designer - Gamatechno',
            'deskripsi' => "Kesempatan magang untuk posisi UI/UX Designer di Gamatechno.\n- Mendesain wireframe & prototype\n- Research user experience\n- Collaboration dengan tim developer",
            'jenis_magang' => 'pilihan',
            'kuota' => 2,
            'kuota_terisi' => 0,
            'syarat_ipk' => 3.00,
            'syarat_semester' => 5,
            'syarat_prodi' => null,
            'dokumen_required' => ['krs', 'transkip_nilai', 'cv', 'surat_lamaran', 'proposal_magang'],
            'tanggal_buka' => '2026-04-01',
            'tanggal_tutup' => '2026-05-15',
            'tanggal_mulai_magang' => '2026-06-01',
            'tanggal_selesai_magang' => '2026-11-30',
            'is_published' => true,
            'is_full' => false,
        ]);
    }
}
