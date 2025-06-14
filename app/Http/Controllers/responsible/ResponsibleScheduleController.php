<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\Departement;
use App\Models\Student;
use App\Models\ResponsibleUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsibleScheduleController extends Controller
{
    public function index()
    {
        // Get responsible user data
        $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
        
        if (!$responsibleUser) {
            return view('pages.responsible.schedule.index', [
                'todaySchedules' => [],
                'schedules' => [],
                'departments' => [],
                'students' => [],
                'currentClass' => null
            ]);
        }

        // Get stase IDs
        $staseIds = $responsibleUser->stases->pluck('id');

        // Fix the query for today's schedules to properly check date range
        $todaySchedules = Schedule::with(['internshipClass.classYear', 'stase.departement'])
            ->whereIn('stase_id', $staseIds)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        // Get all schedules for table, ordered by start_date
        $schedules = Schedule::with(['internshipClass', 'stase.departement'])
            ->whereIn('stase_id', $staseIds)
            ->orderBy('start_date', 'asc')
            ->get();

        // Get all departments for filter
        $departments = Departement::all();

        // Get students for current class
        $currentSchedule = $schedules->first();
        $students = [];
        $currentClass = null;

        if ($currentSchedule) {
            $currentClass = $currentSchedule->internshipClass;
            // Load stase relationship
            $currentSchedule->load('stase');
            
            $students = Student::with(['user', 'studyProgram'])
                ->where('internship_class_id', $currentSchedule->internship_class_id)
                ->get();
        }

        // Get stases for filter
        $stases = Stase::whereIn('id', $staseIds)->get();
        
        // Get internship classes for filter
        $internshipClasses = Schedule::whereIn('stase_id', $staseIds)
            ->with('internshipClass')
            ->get()
            ->pluck('internshipClass')
            ->unique('id');

        return view('pages.responsible.schedule.index', 
            compact('todaySchedules', 'schedules', 'departments', 'students', 
                    'currentClass', 'currentSchedule', 'stases', 'internshipClasses')
        );
    }

    public function getSchedules(Request $request) 
    {
        try {
            $date = $request->date;
            
            // Get responsible user data
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => true,
                    'schedules' => []
                ]);
            }

            // Get stase IDs
            $staseIds = $responsibleUser->stases->pluck('id');
            
            // Only fetch schedules for stases assigned to the responsible user
            $schedules = Schedule::with(['stase.departement', 'internshipClass.classYear'])
                ->whereIn('stase_id', $staseIds)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->get();
                
            return response()->json([
                'success' => true,
                'schedules' => $schedules->map(function($schedule) {
                    return [
                        'class_name' => $schedule->internshipClass->name ?? 'N/A',
                        'academic_year' => $schedule->internshipClass->classYear->class_year ?? null,
                        'stase_name' => $schedule->stase->name ?? 'N/A',
                        'department' => $schedule->stase->departement->name ?? 'N/A',
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Get responsible user data
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak memiliki akses sebagai responsible'
                ]);
            }

            // Get stase IDs
            $staseIds = $responsibleUser->stases->pluck('id');

            // Build query
            $query = Schedule::with(['internshipClass.classYear', 'stase.departement'])
                ->whereIn('stase_id', $staseIds);

            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($subQ) use ($startDate, $endDate) {
                          $subQ->where('start_date', '<=', $startDate)
                               ->where('end_date', '>=', $endDate);
                      });
                });
            }

            $schedules = $query->orderBy('start_date', 'asc')->get();

            // Transform data for frontend
            $transformedSchedules = $schedules->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'stase' => [
                        'id' => $schedule->stase->id ?? null,
                        'name' => $schedule->stase->name ?? 'N/A'
                    ],
                    'internship_class' => [
                        'id' => $schedule->internshipClass->id ?? null,
                        'name' => $schedule->internshipClass->name ?? 'N/A',
                        'class_year' => $schedule->internshipClass->classYear->class_year ?? null
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'schedules' => $transformedSchedules,
                'message' => 'Data berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClassDetails(Request $request)
    {
        try {
            $staseId = $request->input('stase_id');
            $classId = $request->input('class_id');

            if (!$staseId || !$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter stase_id dan class_id diperlukan'
                ]);
            }

            // Get students for the specific class and stase - HAPUS KONDISI STATUS
            $students = Student::with(['user', 'studyProgram.campus', 'internshipClass.classYear'])
                ->where('internship_class_id', $classId)
                ->get(); // Hapus ->where('status', 'active')

            return response()->json([
                'success' => true,
                'students' => $students,
                'message' => 'Data mahasiswa berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClassesForStase(Request $request)
    {
        try {
            $staseId = $request->input('stase_id');

            if (!$staseId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter stase_id diperlukan'
                ]);
            }

            // Get responsible user data
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak memiliki akses sebagai responsible'
                ]);
            }

            // Get classes for the specific stase
            $classes = Schedule::with(['internshipClass.classYear'])
                ->where('stase_id', $staseId)
                ->get()
                ->pluck('internshipClass')
                ->unique('id')
                ->values();

            return response()->json([
                'success' => true,
                'classes' => $classes,
                'message' => 'Data kelas berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentDetail(Request $request)
    {
        try {
            $studentId = $request->input('student_id');

            if (!$studentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter student_id diperlukan'
                ]);
            }

            $student = Student::with(['user', 'studyProgram.campus', 'internshipClass.classYear'])
                ->find($studentId);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan'
                ]);
            }

            return response()->json([
                'success' => true,
                'student' => $student,
                'message' => 'Data mahasiswa berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}