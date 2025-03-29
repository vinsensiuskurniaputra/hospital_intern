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
        // Menu utama
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'url' => '/home',
            'icon' => 'bi bi-house-door', // Bootstrap Icons
            'parent_id' => null,
            'order' => 1,
        ]);

        $userManagement = Menu::create([
            'name' => 'User Management',
            'url' => '/users',
            'icon' => 'bi bi-people', // Bootstrap Icons
            'parent_id' => null,
            'order' => 2,
        ]);

        $permissionManagement = Menu::create([
            'name' => 'Permission',
            'url' => '/permissions',
            'icon' => 'bi bi-shield-lock',
            'parent_id' => null,
            'order' => 3,
        ]);

        $academicManagement = Menu::create([
            'name' => 'Academic Management',
            'url' => '/academics',
            'icon' => 'bi bi-mortarboard',
            'parent_id' => null,
            'order' => 4,
        ]);

        // Submenu
        Menu::create([
            'name' => 'Admins',
            'url' => '/users/admins',
            'icon' => 'bi bi-person-badge', // Ikon lebih sesuai untuk admin
            'parent_id' => $userManagement->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Responsibles',
            'url' => '/users/responsibles',
            'icon' => 'bi bi-person-check', // Ikon untuk pengguna yang bertanggung jawab
            'parent_id' => $userManagement->id,
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Students',
            'url' => '/users/students',
            'icon' => 'bi bi-mortarboard', // Ikon yang lebih cocok untuk mahasiswa/pelajar
            'parent_id' => $userManagement->id,
            'order' => 3,
        ]);

        Menu::create([
            'name' => 'User Authorization ',
            'url' => '/permissions/users',
            'icon' => 'bi bi-person-lock', // Ikon lebih sesuai untuk admin
            'parent_id' => $permissionManagement->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Roles',
            'url' => '/permissions/roles',
            'icon' => 'bi bi-person-gear', // Lebih cocok untuk peran/role
            'parent_id' => $permissionManagement->id,
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Menus',
            'url' => '/permissions/menus',
            'icon' => 'bi bi-list', // Lebih cocok untuk menu
            'parent_id' => $permissionManagement->id,
            'order' => 3,
        ]);

        Menu::create([
            'name' => 'Campuses',
            'url' => '/academic/campuses',
            'icon' => 'bi bi-buildings', // Lebih cocok untuk menu
            'parent_id' => $academicManagement->id,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Study Programs',
            'url' => '/academic/studyPrograms',
            'icon' => 'bi bi-book', // Lebih cocok untuk menu
            'parent_id' => $academicManagement->id,
            'order' => 2,
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        // Ambil semua menu
        $menus = Menu::all();

        // Hubungkan admin dengan semua menu
        $adminRole->menus()->attach($menus->pluck('id'));
    }
}
