<?php

namespace Database\Seeders;

use App\Models\AttendanceExcuse;
use App\Models\Student;
use App\Models\PresenceSession;
use App\Models\Presence;
use Illuminate\Database\Seeder;

class AttendanceExcuseSeeder extends Seeder
{
    public function run(): void
    {
        // Find presences where status is 'sick' or 'absent'
        $absences = Presence::whereIn('status', ['sick', 'absent'])->get();

        if ($absences->isEmpty()) {
            echo "Warning: No absences found. Run PresenceSeeder first.\n";
            return;
        }

        // For each absence, create an excuse
        foreach ($absences as $absence) {
            $excuseTypes = [
                'sick' => ['Sakit Demam', 'Sakit Flu', 'Covid-19', 'Rawat Inap'],
                'absent' => ['Keperluan Keluarga', 'Transportasi Terhambat', 'Kecelakaan', 'Izin Khusus']
            ];

            $status = ['pending', 'approved', 'rejected'];
            $statusWeight = [3, 6, 1]; // 30% pending, 60% approved, 10% rejected
            $randomStatus = $this->weightedRandom($status, $statusWeight);
            
            $excuseType = $excuseTypes[$absence->status][array_rand($excuseTypes[$absence->status])];
            
            AttendanceExcuse::create([
                'student_id' => $absence->student_id,
                'presence_sessions_id' => $absence->presence_sessions_id,
                'detail' => $excuseType . ': ' . $this->generateRandomExcuseDetail($excuseType),
                'letter_url' => rand(0, 1) ? 'excuses/letter_' . $absence->student_id . '_' . time() . '.pdf' : null,
                'status' => $randomStatus,
                'created_at' => $absence->created_at,
                'updated_at' => $absence->updated_at,
            ]);
        }
    }

    private function generateRandomExcuseDetail($type)
    {
        $details = [
            'Sakit Demam' => ['Demam tinggi 39°C', 'Demam disertai sakit kepala', 'Demam dan lemas'],
            'Sakit Flu' => ['Flu berat dan pilek', 'Flu disertai batuk', 'Flu dan sakit tenggorokan'],
            'Covid-19' => ['Hasil test positif', 'Sedang isolasi mandiri', 'Kontak dengan pasien positif'],
            'Rawat Inap' => ['Dirawat di RS Kariadi', 'Operasi mendadak', 'Observasi medis'],
            'Keperluan Keluarga' => ['Acara keluarga besar', 'Kedukaan keluarga', 'Mengantar keluarga sakit'],
            'Transportasi Terhambat' => ['Mogok di jalan', 'Banjir', 'Kereta delay'],
            'Kecelakaan' => ['Kecelakaan ringan', 'Terjatuh dari motor', 'Terkilir saat menuju kampus'],
            'Izin Khusus' => ['Mengikuti kompetisi', 'Mewakili kampus di event', 'Tugas khusus dari jurusan']
        ];

        if (isset($details[$type])) {
            return $details[$type][array_rand($details[$type])];
        }

        return 'Alasan tidak dapat hadir';
    }

    private function weightedRandom($values, $weights)
    {
        $count = count($values);
        $i = 0;
        $n = 0;
        $num = mt_rand(0, array_sum($weights));
        
        while ($i < $count) {
            $n += $weights[$i];
            if ($n >= $num) {
                break;
            }
            $i++;
        }
        
        return $values[$i];
    }
}