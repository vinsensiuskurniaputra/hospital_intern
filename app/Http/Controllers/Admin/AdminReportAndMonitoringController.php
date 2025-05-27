<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Models\ReportAndMonitoring;
use App\Http\Controllers\Controller;

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
        return view('pages.admin.report_and_monitoring.index', compact('countStudent', 'countPIC', 'countAdmin'));
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
