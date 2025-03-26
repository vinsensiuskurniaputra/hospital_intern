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


        $adminRole = Role::where('name', 'admin')->first();

        // Ambil semua menu
        $menus = Menu::all();

        // Hubungkan admin dengan semua menu
        $adminRole->menus()->attach($menus->pluck('id'));
    }
}
