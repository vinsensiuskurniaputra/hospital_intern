<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Models\ReportAndMonitoring;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\Presence;
use App\Models\StudentComponentGrade;
use App\Models\InternshipClass;

class AdminReportAndMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $internshipClassId = $request->get('internship_class_id');

        $studentsQuery = Student::query();
        if ($internshipClassId) {
            $studentsQuery->where('internship_class_id', $internshipClassId);
        }

        $countStudent = $studentsQuery->count();
        $countPIC = ResponsibleUser::count();
        $countAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        // Attendance chart data
        $attendanceData = Presence::selectRaw('MONTH(date_entry) as month, COUNT(*) as count')
            ->whereYear('date_entry', now()->year)
            ->when($internshipClassId, function ($query) use ($internshipClassId) {
                $query->whereHas('student', function ($q) use ($internshipClassId) {
                    $q->where('internship_class_id', $internshipClassId);
                });
            })
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $attendanceChart = [
            'labels' => [],
            'data' => []
        ];

        for ($month = 1; $month <= 12; $month++) {
            $monthName = \Carbon\Carbon::create()->month($month)->format('M');
            $attendanceChart['labels'][] = $monthName;
            $attendanceChart['data'][] = $attendanceData->firstWhere('month', $month)->count ?? 0;
        }

        // Performance chart data
        $performanceData = StudentComponentGrade::selectRaw('stase_id, AVG(value) as avg_grade')
            ->when($internshipClassId, function ($query) use ($internshipClassId) {
                $query->whereHas('student', function ($q) use ($internshipClassId) {
                    $q->where('internship_class_id', $internshipClassId);
                });
            })
            ->groupBy('stase_id')
            ->with('stase')
            ->get();

        $performanceChart = [
            'labels' => $performanceData->pluck('stase.name')->toArray(),
            'data' => $performanceData->pluck('avg_grade')->toArray()
        ];

        $internshipClasses = InternshipClass::all();

        return view('pages.admin.report_and_monitoring.index', compact(
            'countStudent',
            'countPIC',
            'countAdmin',
            'attendanceChart',
            'performanceChart',
            'internshipClasses'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportAndMonitoring $reportAndMonitoring)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportAndMonitoring $reportAndMonitoring)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReportAndMonitoring $reportAndMonitoring)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportAndMonitoring $reportAndMonitoring)
    {
        //
    }

    public function export(Request $request)
    {
        $internshipClassId = $request->get('internship_class_id');

        $studentsQuery = Student::with(['user', 'presences']);
        if ($internshipClassId) {
            $studentsQuery->where('internship_class_id', $internshipClassId);
        }
        $students = $studentsQuery->get();

        $csvData = [];
        $csvData[] = ['Nama Mahasiswa', 'NIM', 'Jumlah Kehadiran', 'Keterangan'];

        foreach ($students as $student) {
            $csvData[] = [
                $student->user->name,
                $student->nim,
                $student->presences->count(),
                $student->is_finished ? 'Selesai' : 'Belum Selesai'
            ];
        }

        $filename = 'laporan_monitoring_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
