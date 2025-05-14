<?php

namespace Database\Seeders;

use App\Models\GradeComponent;
use App\Models\Stase;
use Illuminate\Database\Seeder;

class GradeComponentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all stases
        $stases = Stase::all();

        if ($stases->isEmpty()) {
            echo "Warning: No stases found. Run StaseSeeder first.\n";
            return;
        }

        // Components for each stase
        $commonComponents = [
            'Kehadiran',
            'Sikap',
            'Keterampilan Klinis',
            'Pengetahuan Teoritis',
            'Kemampuan Diagnosis',
            'Kemampuan Terapeutik'
        ];

        // Create different grade components for each stase
        foreach ($stases as $stase) {
            foreach ($commonComponents as $component) {
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => $component
                ]);
            }

            // Add stase-specific components
            if ($stase->name == 'Stase Bedah') {
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Teknik Operasi'
                ]);
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Penanganan Kasus Trauma'
                ]);
            } elseif ($stase->name == 'Stase Anak') {
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Pendekatan Pada Anak'
                ]);
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Manajemen Imunisasi'
                ]);
            } elseif ($stase->name == 'Stase Gigi') {
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Perawatan Gigi'
                ]);
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => 'Kesehatan Mulut'
                ]);
            }
        }
    }
}