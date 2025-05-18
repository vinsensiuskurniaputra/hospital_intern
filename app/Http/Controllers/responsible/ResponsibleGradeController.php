<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\GradeComponent;
use App\Models\StudentGrade;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\InternshipClass;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResponsibleGradeController extends Controller
{
    public function index()
    {
        // Data dummy untuk tampilan
        $data = [
            'kelas' => 'FK-01',
            'status' => 'Default Start',
            'kampus' => 'Universitas Brawijaya',
            'stase' => 'Bedah Umum',
            'mahasiswa' => [
                [
                    'nama' => 'James N. McKinley',
                    'kampus' => 'Universitas Diponegoro'
                ],
                [
                    'nama' => 'Sarah Johnson',
                    'kampus' => 'Universitas Brawijaya'
                ],
                [
                    'nama' => 'Michael Smith',
                    'kampus' => 'Universitas Indonesia'
                ]
            ]
        ];

        return view('pages.responsible.grades.index', $data);
    }

    // ...rest of the controller remains the same...
}