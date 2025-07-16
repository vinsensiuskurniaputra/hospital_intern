<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResponsibleReportExport implements FromView
{
    public $students, $stase, $absensiData, $gradesData, $gradeComponents, $componentGrades, $wantedComponents;

    public function __construct($students, $stase, $absensiData, $gradesData, $gradeComponents, $componentGrades, $wantedComponents = null)
    {
        $this->students = $students;
        $this->stase = $stase;
        $this->absensiData = $absensiData;
        $this->gradesData = $gradesData;
        $this->gradeComponents = $gradeComponents;
        $this->componentGrades = $componentGrades;
        $this->wantedComponents = $wantedComponents;
    }

    public function view(): View
    {
        return view('exports.responsible_report_stase', [
            'students' => $this->students,
            'stase' => $this->stase,
            'absensiData' => $this->absensiData,
            'gradesData' => $this->gradesData,
            'gradeComponents' => $this->gradeComponents,
            'componentGrades' => $this->componentGrades,
            'wantedComponents' => $this->wantedComponents,
        ]);
    }
}
