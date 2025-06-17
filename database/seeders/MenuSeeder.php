<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General
        $dashboard = Menu::create([
            'name' => 'Beranda',
            'url' => '/home',
            'icon' => 'bi bi-house-door', // Bootstrap Icons
            'parent_id' => null,
            'order' => 1,
        ]);

        // --- Menu Admin ---
        // Menu utama

        $academicManagement = Menu::create([
            'name' => 'Manajemen Akademik',
            'url' => '/academics',
            'icon' => 'bi bi-mortarboard',
            'parent_id' => null,
            'order' => 2,
        ]);

        $userManagement = Menu::create([
            'name' => 'Manajemen Pengguna',
            'url' => '/users',
            'icon' => 'bi bi-people', // Bootstrap Icons
            'parent_id' => null,
            'order' => 3,
        ]);

        $permissionManagement = Menu::create([
            'name' => 'Izin',
            'url' => '/permissions',
            'icon' => 'bi bi-shield-lock',
            'parent_id' => null,
            'order' => 4,
        ]);

        $internshipManagement = Menu::create([
            'name' => 'Manajemen Magang',
            'url' => '/internships',
            'icon' => 'bi bi-person-workspace',
            'parent_id' => null,
            'order' => 5,
        ]);

        $presenceManagement = Menu::create([
            'name' => 'Manajemen Presensi',
            'url' => '/presences',
            'icon' => 'bi bi-calendar-check',
            'parent_id' => null,
            'order' => 6,
        ]);

        // Submenu
        $adminManagement = Menu::create([
            'name' => 'Admin',
            'url' => '/users/admins',
            'icon' => 'bi bi-person-badge', // Ikon lebih sesuai untuk admin
            'parent_id' => $userManagement->id,
            'order' => 1,
        ]);
        $resposibleManagement = Menu::create([
            'name' => 'Penanggung Jawab',
            'url' => '/users/responsibles',
            'icon' => 'bi bi-person-check', // Ikon untuk pengguna yang bertanggung jawab
            'parent_id' => $userManagement->id,
            'order' => 2,
        ]);
        $studentManagement = Menu::create([
            'name' => 'Mahasiswa',
            'url' => '/users/students',
            'icon' => 'bi bi-mortarboard', // Ikon yang lebih cocok untuk mahasiswa/pelajar
            'parent_id' => $userManagement->id,
            'order' => 3,
        ]);

        $roleManagement = Menu::create([
            'name' => 'Peran',
            'url' => '/permissions/roles',
            'icon' => 'bi bi-person-gear', // Lebih cocok untuk peran/role
            'parent_id' => $permissionManagement->id,
            'order' => 1,
        ]);

        $userAuthManagement = Menu::create([
            'name' => 'Otorisasi Pengguna',
            'url' => '/permissions/users',
            'icon' => 'bi bi-person-lock', // Ikon lebih sesuai untuk admin
            'parent_id' => $permissionManagement->id,
            'order' => 2,
        ]);
        $menuManagement = Menu::create([
            'name' => 'Menu',
            'url' => '/permissions/menus',
            'icon' => 'bi bi-list', // Lebih cocok untuk menu
            'parent_id' => $permissionManagement->id,
            'order' => 3,
        ]);


        $campusManagement = Menu::create([
            'name' => 'Kampus',
            'url' => '/academics/campuses',
            'icon' => 'bi bi-buildings', // Lebih cocok untuk menu
            'parent_id' => $academicManagement->id,
            'order' => 1,
        ]);
        $studyProgramManagement = Menu::create([
            'name' => 'Program Studi',
            'url' => '/academics/studyPrograms',
            'icon' => 'bi bi-book', // Lebih cocok untuk menu
            'parent_id' => $academicManagement->id,
            'order' => 2,
        ]);
        $classYearManagement = Menu::create([
            'name' => 'Tahun Ajaran',
            'url' => '/academics/classYears',
            'icon' => 'bi bi-calendar3', // Lebih cocok untuk menu
            'parent_id' => $academicManagement->id,
            'order' => 3,
        ]);


        $departementManagement = Menu::create([
            'name' => 'Departemen',
            'url' => '/internships/departements',
            'icon' => 'bi bi-diagram-3', 
            'parent_id' => $internshipManagement->id,
            'order' => 1,
        ]);
        $staseManagement = Menu::create([
            'name' => 'Stase',
            'url' => '/internships/stases',
            'icon' => 'bi bi-signpost-split', 
            'parent_id' => $internshipManagement->id,
            'order' => 2,
        ]);
        $internshipClassManagement = Menu::create([
            'name' => 'Kelas Magang',
            'url' => '/internships/internshipClasses',
            'icon' => 'bi bi-easel', 
            'parent_id' => $internshipManagement->id,
            'order' => 3,
        ]);


        $scheduleManagement = Menu::create([
            'name' => 'Jadwal',
            'url' => '/presences/schedules',
            'icon' => 'bi bi-clock', 
            'parent_id' => $presenceManagement->id,
            'order' => 1,
        ]);
        $studentPresenceManagement = Menu::create([
            'name' => 'Presensi Mahasiswa',
            'url' => '/presences/studentPresences',
            'icon' => 'bi bi-person-check', 
            'parent_id' => $presenceManagement->id,
            'order' => 2,
        ]);
        $studentScoreManagement = Menu::create([
            'name' => 'Nilai Mahasiswa',
            'url' => '/presences/studentScores',
            'icon' => 'bi bi-graph-up', 
            'parent_id' => $presenceManagement->id,
            'order' => 3,
        ]);
        $certificateManagement = Menu::create([
            'name' => 'Sertifikat',
            'url' => '/presences/certificates',
            'icon' => 'bi bi-award', 
            'parent_id' => $presenceManagement->id,
            'order' => 4,
        ]);
        $reportAndMonitoringManagement = Menu::create([
            'name' => 'Laporan & Monitoring',
            'url' => '/presences/reportAndMonitorings',
            'icon' => 'bi bi-bar-chart-line', 
            'parent_id' => $presenceManagement->id,
            'order' => 5,
        ]);

        // gift role
        $adminRole = Role::where('name', 'admin')->first();

        $adminMenus = [
            $dashboard, 
            $userManagement, 
            $permissionManagement, 
            $academicManagement, 
            $internshipManagement, 
            $presenceManagement,
        ];
        $adminRole->menus()->attach(collect($adminMenus)->pluck('id'));


        // Menu untuk mahasiswa (role student)
        $studentSchedule = Menu::create([
            'name' => 'Jadwal',
            'url' => '/student/schedule',
            'icon' => 'bi bi-calendar3',
            'parent_id' => null,
            'order' => 2,
        ]);

        $studentAttendance = Menu::create([
            'name' => 'Presensi & Sertifikasi',
            'url' => '/student/attendance',
            'icon' => 'bi bi-card-checklist',
            'parent_id' => null,
            'order' => 3,
        ]);

        $studentGrades = Menu::create([
            'name' => 'Nilai',
            'url' => '/student/grades',
            'icon' => 'bi bi-award',
            'parent_id' => null,
            'order' => 4,
        ]);
        
        // Add new profile menu for students
        // $studentProfile = Menu::create([
        //     'name' => 'Profile',
        //     'url' => '/student/profile',
        //     'icon' => 'bi bi-person-circle',
        //     'parent_id' => null,
        //     'order' => 5,
        // ]);
        
        // Add new notifications menu for students
        // $studentNotifications = Menu::create([
        //     'name' => 'Notifikasi',
        //     'url' => '/student/notifications',
        //     'icon' => 'bi bi-bell',
        //     'parent_id' => null,
        //     'order' => 6,
        // ]);

        // Mendapatkan role student
        $studentRole = Role::where('name', 'student')->first();

        // Hubungkan menu mahasiswa dengan role student (updated to include new menus)
        $studentMenus = [
            $dashboard, 
            $studentSchedule, 
            $studentAttendance, 
            $studentGrades, 
            // $studentProfile, 
            // $studentNotifications
        ];
        $studentRole->menus()->attach(collect($studentMenus)->pluck('id'));
        
        // Menu untuk Penanggung Jawab (role responsible)

        $responsibleSchedule = Menu::create([
            'name' => 'Jadwal',
            'url' => '/responsible/schedule',
            'icon' => 'bi bi-calendar3',
            'parent_id' => null,
            'order' => 2,
        ]);

        $responsibleAttendance = Menu::create([
            'name' => 'Presensi',
            'url' => '/responsible/attendance',
            'icon' => 'bi bi-card-checklist',
            'parent_id' => null,
            'order' => 3,
        ]);
        
        // $responsibleProfile = Menu::create([
        //     'name' => 'Profile',
        //     'url' => '/responsible/profile',
        //     'icon' => 'bi bi-person-circle',
        //     'parent_id' => null,
        //     'order' => 4,
        // ]);
        
        $responsibleGrades = Menu::create([
            'name' => 'Nilai',
            'url' => '/responsible/grades',
            'icon' => 'bi bi-award',
            'parent_id' => null,
            'order' => 5,
        ]);
        
        $responsibleReports = Menu::create([
            'name' => 'Laporan & Rekapitulasi',
            'url' => '/responsible/reports',
            'icon' => 'bi bi-file-earmark-text',
            'parent_id' => null,
            'order' => 6,
        ]);

        // Mendapatkan role responsible
        $responsibleRole = Role::where('name', 'pic')->first();

        // Hubungkan menu penanggung jawab dengan role responsible
        $responsibleMenus = [
            $dashboard, 
            $responsibleSchedule, 
            $responsibleAttendance, 
            // $responsibleProfile, 
            $responsibleGrades, 
            $responsibleReports
        ];
        $responsibleRole->menus()->attach(collect($responsibleMenus)->pluck('id'));

    }
}
