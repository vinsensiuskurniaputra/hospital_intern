<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class CampusAndStudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Create Campuses
        $polines = Campus::create([
            'name' => 'Politeknik Negeri Semarang',
            'detail' => 'Kampus vokasi unggulan di Jawa Tengah yang fokus pada pendidikan praktis dan aplikatif.'
        ]);

        $undip = Campus::create([
            'name' => 'Universitas Diponegoro',
            'detail' => 'Perguruan tinggi negeri terkemuka di Semarang dengan berbagai program studi unggulan.'
        ]);

        $unnes = Campus::create([
            'name' => 'Universitas Negeri Semarang',
            'detail' => 'Universitas yang berfokus pada pendidikan dengan fasilitas modern dan kurikulum terkini.'
        ]);

        // Create Study Programs for Polines
        StudyProgram::create([
            'name' => 'D3 Teknik Informatika',
            'campus_id' => $polines->id
        ]);

        StudyProgram::create([
            'name' => 'D4 Teknik Informatika',
            'campus_id' => $polines->id
        ]);

        StudyProgram::create([
            'name' => 'D3 Teknik Elektro',
            'campus_id' => $polines->id
        ]);

        // Create Study Programs for UNDIP
        StudyProgram::create([
            'name' => 'S1 Kedokteran',
            'campus_id' => $undip->id
        ]);

        StudyProgram::create([
            'name' => 'S1 Teknik Kimia',
            'campus_id' => $undip->id
        ]);

        StudyProgram::create([
            'name' => 'S1 Farmasi',
            'campus_id' => $undip->id
        ]);

        // Create Study Programs for UNNES
        StudyProgram::create([
            'name' => 'S1 Pendidikan Dokter',
            'campus_id' => $unnes->id
        ]);

        StudyProgram::create([
            'name' => 'S1 Keperawatan',
            'campus_id' => $unnes->id
        ]);

        StudyProgram::create([
            'name' => 'S1 Kesehatan Masyarakat',
            'campus_id' => $unnes->id
        ]);
    }
}