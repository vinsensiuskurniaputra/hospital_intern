<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan user student dengan ID 2 ada
        $student = User::find(2);
        
        if (!$student) {
            $this->command->info('User student dengan ID 2 tidak ditemukan.');
            return;
        }
        
        // Notifikasi untuk student
        $studentNotifications = [
            [
                'user_id' => 2, // ID user student dari UserSeeder
                'title' => 'Jadwal Praktikum Diperbarui',
                'message' => 'Jadwal praktikum di Departemen Bedah telah diperbarui. Silakan periksa halaman jadwal.',
                'type' => 'info',
                'is_read' => false,
                'icon' => 'bi bi-calendar-event',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'user_id' => 2,
                'title' => 'Pengumpulan Laporan',
                'message' => 'Jangan lupa untuk mengumpulkan laporan praktikum Anda paling lambat hari Jumat, 8 Mei 2025.',
                'type' => 'warning',
                'is_read' => false,
                'icon' => 'bi bi-exclamation-triangle',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'user_id' => 2,
                'title' => 'Nilai Praktikum',
                'message' => 'Nilai praktikum Anda di Departemen Radiologi telah diinput. Silakan periksa pada halaman nilai.',
                'type' => 'info',
                'is_read' => true,
                'icon' => 'bi bi-award',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'user_id' => 2,
                'title' => 'Absensi Tidak Lengkap',
                'message' => 'Terdapat ketidaklengkapan pada absensi Anda di stase Anak. Harap segera konfirmasi ke penanggung jawab.',
                'type' => 'error',
                'is_read' => true,
                'icon' => 'bi bi-calendar-x',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(6)
            ],
            [
                'user_id' => 2,
                'title' => 'Perubahan Ruangan',
                'message' => 'Ruangan praktikum untuk Departemen Anestesi dipindahkan ke Ruang B-301 mulai minggu depan.',
                'type' => 'info',
                'is_read' => false,
                'icon' => 'bi bi-building',
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subHours(12)
            ]
        ];
        
        // Insert data
        DB::table('notifications')->insert($studentNotifications);
        
        $this->command->info('Notifikasi untuk student berhasil ditambahkan.');
    }
}