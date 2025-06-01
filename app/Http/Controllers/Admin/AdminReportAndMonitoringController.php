<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Models\ReportAndMonitoring;
use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\StudentComponentGrade;

class AdminReportAndMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countStudent = Student::count();
        $countPIC = ResponsibleUser::count();
        $countAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        // Attendance chart data
        $attendanceData = Presence::selectRaw('MONTH(date_entry) as month, COUNT(*) as count')
            ->whereYear('date_entry', now()->year)
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
            ->groupBy('stase_id')
            ->with('stase')
            ->get();

        $performanceChart = [
            'labels' => $performanceData->pluck('stase.name')->toArray(),
            'data' => $performanceData->pluck('avg_grade')->toArray()
        ];

        return view('pages.admin.report_and_monitoring.index', compact(
            'countStudent',
            'countPIC',
            'countAdmin',
            'attendanceChart',
            'performanceChart'
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
}
