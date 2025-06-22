<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Stase;
use App\Models\InternshipClass;
use App\Models\ResponsibleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\ResponsibleReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ResponsibleReportController extends Controller
{
    /**
     * Display the reports and summary page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the responsible user record
        $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();

        if (!$responsibleUser) {
            return redirect()->back()->with('error', 'No responsible user record found');
        }

        // Get only stases assigned to this responsible user
        $stases = Stase::whereHas('responsibleUsers', function($query) use ($responsibleUser) {
            $query->where('responsible_user_id', $responsibleUser->id);
        })->get();

        if ($stases->isEmpty()) {
            Log::warning('User ID ' . Auth::id() . ' has no assigned stases.');
            return view('pages.responsible.reports.index', [
                'students' => collect(),
                'stases' => collect(),
                'internshipClasses' => collect(),
                'averageGrades' => collect(),
                'topPerformers' => collect(),
                'overallAverage' => 0,
                'classYears' => collect(),
                'currentYear' => date('Y'),
                'staseName' => 'None'
            ])->with('error', 'No stases assigned to your account');
        }

        // Default to the first stase assigned to the user if no stase is selected
        $stase = $request->filled('stase') ?
            Stase::whereIn('id', $stases->pluck('id'))
                ->where('id', $request->stase)
                ->first() :
            $stases->first();

        // Get only internship classes that have schedules with the user's stases
        $internshipClasses = InternshipClass::whereHas('schedules', function($query) use ($stases) {
            $query->whereIn('stase_id', $stases->pluck('id'));
        })->get();

        // Filter students
        $studentsQuery = Student::with(['user', 'internshipClass.classYear', 'studyProgram.campus'])
            ->whereHas('internshipClass', function($query) use ($stase) {
                $query->whereHas('schedules', function($q) use ($stase) {
                    $q->where('stase_id', $stase->id);
                });
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function($query) use ($search) {
                $query->where('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('internship_class')) {
            $studentsQuery->where('internship_class_id', $request->internship_class);
        }
        if ($request->filled('class_year')) {
            $studentsQuery->whereHas('internshipClass', function($query) use ($request) {
                $query->where('class_year_id', $request->class_year);
            });
        }

        $students = $studentsQuery->get();
        $studentIds = $students->pluck('id')->toArray();

        // --- ABSENSI AGGREGATE QUERY ---
        $absensiData = DB::table('presences')
            ->select(
                'students.id as student_id',
                'users.name as student_name',
                'students.nim',
                'internship_classes.name as class_name',
                DB::raw('COUNT(presences.status) as total_presensi'),
                DB::raw('COUNT(CASE WHEN presences.status = "present" THEN 1 END) as present'),
                DB::raw('COUNT(CASE WHEN presences.status = "izin" THEN 1 END) as izin'),
                DB::raw('COUNT(CASE WHEN presences.status = "sakit" THEN 1 END) as sakit'),
                DB::raw('COUNT(CASE WHEN presences.status = "alpa" THEN 1 END) as alpa'),
                DB::raw('ROUND(SUM(IF(presences.status = "present", 1, 0)) / COUNT(presences.status) * 100, 2) as persentase_present')
            )
            ->join('students', 'presences.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('internship_classes', 'students.internship_class_id', '=', 'internship_classes.id')
            ->whereIn('students.id', $studentIds)
            ->groupBy('students.id', 'users.name', 'students.nim', 'internship_classes.name')
            ->get()
            ->keyBy('student_id');

        // --- NILAI AGGREGATE QUERY ---
        $gradesData = DB::table('student_grades')
            ->select(
                'student_id',
                DB::raw('AVG(avg_grades) as average_grade')
            )
            ->whereIn('student_id', $studentIds)
            ->where('stase_id', $stase->id)
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        // Attach absensi & grade data to each student
        foreach ($students as $student) {
            $absensi = $absensiData->get($student->id);
            $student->attendance_percentage = $absensi ? $absensi->persentase_present : 0;
            $student->total_presensi = $absensi ? $absensi->total_presensi : 0;
            $student->present = $absensi ? $absensi->present : 0;
            $student->izin = $absensi ? $absensi->izin : 0;
            $student->sakit = $absensi ? $absensi->sakit : 0;
            $student->alpa = $absensi ? $absensi->alpa : 0;
            $student->average_grade = $gradesData->get($student->id)->average_grade ?? 0;
        }

        // --- TOP PERFORMERS & AVERAGE ---
        $topPerformers = DB::table('student_grades')
            ->select('student_id', DB::raw('AVG(avg_grades) as average_grade'))
            ->whereIn('student_id', $studentIds)
            ->where('stase_id', $stase->id)
            ->groupBy('student_id')
            ->orderBy('average_grade', 'desc')
            ->take(5)
            ->get();

        // Ambil data user untuk top performers
        $topPerformerIds = $topPerformers->pluck('student_id')->toArray();
        $topPerformerUsers = Student::with('user')->whereIn('id', $topPerformerIds)->get()->keyBy('id');
        foreach ($topPerformers as $performer) {
            $performer->student = $topPerformerUsers->get($performer->student_id);
        }

        $overallAverage = $gradesData->avg('average_grade') ?? 0;

        // Unique class years for filters
        $classYears = $students->map(function($student) {
            return $student->internshipClass->classYear ?? null;
        })->filter()->unique('id');

        $currentYear = date('Y');
        $staseName = $stase->name ?? 'Semua';

        // Grade distribution for donut chart
        $gradeDistribution = DB::table('student_grades')
            ->whereIn('student_id', $studentIds)
            ->where('stase_id', $stase->id)
            ->select(
                DB::raw('COUNT(CASE WHEN avg_grades >= 90 THEN 1 END) as above90'),
                DB::raw('COUNT(CASE WHEN avg_grades >= 70 AND avg_grades < 90 THEN 1 END) as above70'),
                DB::raw('COUNT(CASE WHEN avg_grades >= 50 AND avg_grades < 70 THEN 1 END) as above50'),
                DB::raw('COUNT(*) as total')
            )
            ->first();

        $totalStudents = $gradeDistribution->total ?: 1;
        $gradePercentages = [
            'above90' => round(($gradeDistribution->above90 / $totalStudents) * 100, 1),
            'above70' => round(($gradeDistribution->above70 / $totalStudents) * 100, 1),
            'above50' => round(($gradeDistribution->above50 / $totalStudents) * 100, 1)
        ];

        return view('pages.responsible.reports.index', compact(
            'students',
            'topPerformers',
            'overallAverage',
            'classYears',
            'stases',
            'internshipClasses',
            'stase',
            'currentYear',
            'staseName',
            'gradePercentages'
        ));
    }

    public function downloadExcel(Request $request)
    {
        $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
        if (!$responsibleUser) {
            return redirect()->back()->with('error', 'No responsible user record found');
        }

        $stases = Stase::whereHas('responsibleUsers', function($query) use ($responsibleUser) {
            $query->where('responsible_user_id', $responsibleUser->id);
        })->get();

        $stase = $request->filled('stase') ?
            Stase::whereIn('id', $stases->pluck('id'))->where('id', $request->stase)->first() :
            $stases->first();

        $students = Student::with(['user', 'internshipClass.classYear', 'studyProgram.campus'])
            ->whereHas('internshipClass', function($query) use ($stase) {
                $query->whereHas('schedules', function($q) use ($stase) {
                    $q->where('stase_id', $stase->id);
                });
            })->get();

        $studentIds = $students->pluck('id')->toArray();

        $wantedComponents = [
            'Keahlian', 'Profesionalisme', 'Komunikasi', 'Kemampuan Menangani Pasien'
        ];

        $gradeComponents = DB::table('grade_components')
            ->where('stase_id', $stase->id)
            ->pluck('name', 'id');

        $componentGrades = DB::table('student_component_grades')
            ->where('stase_id', $stase->id)
            ->whereIn('student_id', $studentIds)
            ->select('student_id', 'grade_component_id', 'value')
            ->get()
            ->groupBy('student_id');

        $absensiData = DB::table('presences')
            ->select(
                'students.id as student_id',
                DB::raw('COUNT(*) as total_presensi'),
                DB::raw('SUM(CASE WHEN presences.status = "present" THEN 1 ELSE 0 END) as hadir'),
                DB::raw('SUM(CASE WHEN presences.status = "sick" THEN 1 ELSE 0 END) as sakit'),
                DB::raw('SUM(CASE WHEN presences.status = "absent" THEN 1 ELSE 0 END) as alpa'),
                DB::raw('SUM(CASE WHEN presences.status = "izin" OR presences.status = "excused" THEN 1 ELSE 0 END) as izin'),
                DB::raw('ROUND(SUM(CASE WHEN presences.status = "present" THEN 1 ELSE 0 END) / COUNT(*) * 100, 0) as persentase_present')
            )
            ->join('students', 'presences.student_id', '=', 'students.id')
            ->whereIn('students.id', $studentIds)
            ->groupBy('students.id')
            ->get()
            ->keyBy('student_id');

        $gradesData = DB::table('student_grades')
            ->select(
                'student_id',
                DB::raw('AVG(avg_grades) as average_grade')
            )
            ->whereIn('student_id', $studentIds)
            ->where('stase_id', $stase->id)
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        $filename = 'rekapitulasi_stase_' . ($stase->name ?? 'all') . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new ResponsibleReportExport($students, $stase, $absensiData, $gradesData, $gradeComponents, $componentGrades, $wantedComponents),
            $filename
        );
    }
}