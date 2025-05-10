<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\Presence;
use App\Models\PresenceSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ResponsibleAttendanceController extends Controller
{
    /**
     * Display attendance management page for responsible
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get stases that this responsible manages
        $stases = Stase::where('responsible_user_id', $userId)->get();
        
        // Initialize variables - ensure they're never null
        $staseIds = $stases->pluck('id')->toArray();
        $schedules = collect(); // Empty collection instead of null
        $activeSessions = collect();
        $studentCounts = [];
        
        // Only proceed if there are stases
        if (count($staseIds) > 0) {
            // Get schedules for these stases
            $schedules = Schedule::whereIn('stase_id', $staseIds)
                ->with(['stase', 'internshipClass'])
                ->orderBy('start_date', 'desc')
                ->get();
            
            // Get today's active sessions
            $today = Carbon::now()->format('Y-m-d');
            $activeSessions = PresenceSession::whereIn('schedule_id', $schedules->pluck('id')->toArray())
                ->whereDate('date', $today)
                ->get();
            
            // Count students for each stase
            foreach ($stases as $stase) {
                // Get schedules for this stase
                $staseSchedules = $schedules->where('stase_id', $stase->id);
                
                // Get unique internship class IDs from these schedules
                $classIds = $staseSchedules->pluck('internship_class_id')->filter()->unique()->toArray();
                
                // Count students in these classes
                $count = !empty($classIds) ? Student::whereIn('internship_class_id', $classIds)->count() : 0;
                $studentCounts[$stase->id] = $count;
            }
        }
        
        return view('pages.responsible.attendance.index', [
            'stases' => $stases, 
            'schedules' => $schedules,
            'activeSessions' => $activeSessions,
            'studentCounts' => $studentCounts
        ]);
    }
    
    /**
     * Get students attendance for a specific schedule
     */
    public function getStudentAttendance(Request $request)
    {
        $scheduleId = $request->schedule_id;
        
        // Validate schedule exists and belongs to this responsible
        $schedule = Schedule::findOrFail($scheduleId);
        $stase = Stase::findOrFail($schedule->stase_id);
        
        if ($stase->responsible_user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke jadwal ini'
            ], 403);
        }
        
        // Get date parameter or use today
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        
        // Get presence session for this schedule and date
        $presenceSession = PresenceSession::where('schedule_id', $scheduleId)
            ->whereDate('date', $date)
            ->first();
            
        // Get students in this schedule's internship class
        $students = Student::where('internship_class_id', $schedule->internship_class_id)
            ->with('user')
            ->get();
            
        $attendanceData = [];
        
        foreach ($students as $student) {
            $presence = null;
            
            if ($presenceSession) {
                $presence = Presence::where('student_id', $student->id)
                    ->where('presence_sessions_id', $presenceSession->id)
                    ->whereDate('date_entry', $date)
                    ->first();
            }
            
            $attendanceData[] = [
                'student_id' => $student->id,
                'name' => $student->user->name,
                'nim' => $student->nim,
                'present' => $presence ? true : false,
                'check_in' => $presence ? $presence->time_check_in : null,
                'check_out' => $presence ? $presence->time_check_out : null,
                'status' => $presence ? $presence->status : 'absent'
            ];
        }
        
        return response()->json([
            'status' => true,
            'data' => [
                'schedule' => [
                    'id' => $schedule->id,
                    'stase' => $stase->name,
                    'class' => $schedule->internshipClass->name ?? 'Unknown',
                    'date' => $date
                ],
                'session' => $presenceSession ? [
                    'id' => $presenceSession->id,
                    'token' => $presenceSession->token,
                    'start_time' => $presenceSession->start_time,
                    'end_time' => $presenceSession->end_time,
                ] : null,
                'students' => $attendanceData
            ]
        ]);
    }
    
    /**
     * Add manual attendance for a student
     */
    public function addManualAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'required',
            'status' => 'required|in:present,late,excused,absent'
        ]);
        
        // Validate schedule belongs to this responsible
        $schedule = Schedule::findOrFail($request->schedule_id);
        $stase = Stase::findOrFail($schedule->stase_id);
        
        if ($stase->responsible_user_id != Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke jadwal ini'
            ], 403);
        }
        
        // Get or create presence session
        $presenceSession = PresenceSession::firstOrCreate(
            [
                'schedule_id' => $schedule->id,
                'date' => $request->date,
            ],
            [
                'token' => strtoupper(substr(md5(uniqid()), 0, 6)),
                'expiration_time' => Carbon::parse($request->date)->endOfDay(), // Gunakan end of day
            ]
        );
        
        // Check if student already has attendance for this session
        $existingPresence = Presence::where('student_id', $request->student_id)
            ->where('presence_sessions_id', $presenceSession->id)
            ->whereDate('date_entry', $request->date)
            ->first();
            
        if ($existingPresence) {
            $existingPresence->update([
                'time_check_in' => Carbon::parse($request->check_in)->format('H:i:s'),
                'time_check_out' => Carbon::parse($request->check_out)->format('H:i:s'),
                'status' => $request->status,
            ]);
            
            $presence = $existingPresence;
        } else {
            $presence = Presence::create([
                'student_id' => $request->student_id,
                'presence_sessions_id' => $presenceSession->id,
                'date_entry' => $request->date,
                'time_check_in' => Carbon::parse($request->check_in)->format('H:i:s'),
                'time_check_out' => Carbon::parse($request->check_out)->format('H:i:s'),
                'status' => $request->status,
            ]);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Presensi berhasil ditambahkan',
            'data' => [
                'presence_id' => $presence->id,
                'check_in' => $presence->time_check_in,
                'check_out' => $presence->time_check_out,
                'status' => $presence->status
            ]
        ]);
    }
    
    /**
     * Get attendance statistics for a stase
     */
    public function getAttendanceStatistics(Request $request)
    {
        $staseId = $request->stase_id;
        $userId = Auth::id();
        
        // Validate stase belongs to this responsible
        $stase = Stase::where('id', $staseId)
            ->where('responsible_user_id', $userId)
            ->firstOrFail();
            
        // Get schedules for this stase
        $schedules = Schedule::where('stase_id', $staseId)
            ->pluck('id')
            ->toArray();
            
        // Get presence sessions for these schedules
        $sessionIds = PresenceSession::whereIn('schedule_id', $schedules)
            ->pluck('id')
            ->toArray();
            
        // Get attendance statistics
        $totalStudents = Student::whereHas('presences', function($query) use ($sessionIds) {
                $query->whereIn('presence_sessions_id', $sessionIds);
            })
            ->count();
            
        $presentCount = Presence::whereIn('presence_sessions_id', $sessionIds)
            ->where('status', 'present')
            ->count();
            
        $lateCount = Presence::whereIn('presence_sessions_id', $sessionIds)
            ->where('status', 'late')
            ->count();
            
        $excusedCount = Presence::whereIn('presence_sessions_id', $sessionIds)
            ->where('status', 'excused')
            ->count();
            
        $absentCount = Presence::whereIn('presence_sessions_id', $sessionIds)
            ->where('status', 'absent')
            ->count();
            
        // Get attendance by date (last 7 days)
        $lastWeekDates = [];
        $attendanceByDate = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastWeekDates[] = $date;
            
            $dayPresent = Presence::whereIn('presence_sessions_id', $sessionIds)
                ->whereDate('date_entry', $date)
                ->where('status', 'present')
                ->count();
                
            $dayTotal = Presence::whereIn('presence_sessions_id', $sessionIds)
                ->whereDate('date_entry', $date)
                ->count();
                
            $attendanceByDate[] = [
                'date' => $date,
                'formatted_date' => Carbon::parse($date)->format('d M'),
                'present' => $dayPresent,
                'total' => $dayTotal
            ];
        }
        
        return response()->json([
            'status' => true,
            'data' => [
                'stase' => [
                    'id' => $stase->id,
                    'name' => $stase->name
                ],
                'total_students' => $totalStudents,
                'statistics' => [
                    'present' => $presentCount,
                    'late' => $lateCount,
                    'excused' => $excusedCount,
                    'absent' => $absentCount
                ],
                'by_date' => $attendanceByDate
            ]
        ]);
    }
}