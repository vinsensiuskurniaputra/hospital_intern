<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\InternshipClass;
use App\Models\Presence;
use App\Models\PresenceSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ResponsibleAttendanceController extends Controller
{
    /**
     * Display the responsible attendance page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the logged in responsible user
        $responsibleUser = Auth::user()->responsibleUser;
        
        // Get all stases this responsible user is assigned to
        $stases = $responsibleUser->stases;
        
        // Get default stase (first one)
        $defaultStase = $stases->first();
        
        // Get today's date for default filtering
        $today = Carbon::now()->format('Y-m-d');
        
        // Get internship classes associated with the default stase through schedules
        $internshipClasses = collect();
        
        if ($defaultStase) {
            $internshipClasses = InternshipClass::whereHas('schedules', function($query) use ($defaultStase) {
                $query->where('stase_id', $defaultStase->id);
            })->get();
        }
        
        // Get default internship class (first one)
        $defaultClass = $internshipClasses->first();
        
        // Get students from the default class
        $students = collect();
        
        if ($defaultClass) {
            $students = Student::where('internship_class_id', $defaultClass->id)
                ->with(['user', 'studyProgram', 'presences' => function($query) use ($today) {
                    $query->whereDate('date_entry', $today);
                }])
                ->get();
        }
        
        return view('pages.responsible.attendance.index', compact(
            'stases',
            'defaultStase',
            'internshipClasses',
            'defaultClass',
            'today',
            'students'
        ));
    }
    
    /**
     * Get students based on stase, class, and date filter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentAttendance(Request $request)
    {
        $request->validate([
            'stase_id' => 'required|exists:stases,id',
            'class_id' => 'required|exists:internship_classes,id',
            'date' => 'required|date',
        ]);
        
        $staseId = $request->stase_id;
        $classId = $request->class_id;
        $date = $request->date;
        
        // Get students from the selected class
        $students = Student::where('internship_class_id', $classId)
            ->with(['user', 'studyProgram', 'presences' => function($query) use ($date) {
                $query->whereDate('date_entry', $date);
            }])
            ->get();
            
        return response()->json([
            'success' => true,
            'students' => $students,
        ]);
    }
    
    /**
     * Get internship classes based on stase filter
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternshipClasses(Request $request)
    {
        $request->validate([
            'stase_id' => 'required|exists:stases,id',
        ]);
        
        $staseId = $request->stase_id;
        
        // Get internship classes associated with the selected stase through schedules
        $internshipClasses = InternshipClass::whereHas('schedules', function($query) use ($staseId) {
            $query->where('stase_id', $staseId);
        })->get();
        
        return response()->json([
            'success' => true,
            'classes' => $internshipClasses,
        ]);
    }

    /**
     * Update attendance status for a student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,sick,excused,absent',
        ]);
        
        $studentId = $request->student_id;
        $date = $request->date;
        $status = $request->status;
        
        // Check if attendance record exists
        $presence = Presence::where('student_id', $studentId)
            ->whereDate('date_entry', $date)
            ->first();
            
        $previousStatus = null;
        
        if ($presence) {
            $previousStatus = $presence->status;
            $presence->update([
                'status' => $status,
            ]);
        } else {
            // Create new attendance record
            $presence = Presence::create([
                'student_id' => $studentId,
                'status' => $status,
                'date_entry' => $date,
                'check_in' => now()->format('H:i:s'),
                'check_out' => now()->format('H:i:s'),
                // Need to set presence_sessions_id
                'presence_sessions_id' => $this->getOrCreatePresenceSessionId($studentId, $date),
            ]);
        }
        
        // Get presence session ID from the presence record
        $presenceSessionId = $presence->presence_sessions_id;
        
        // Handle sick or excused status - create/update attendance_excuse record
        if ($status === 'sick' || $status === 'excused') {
            // Handle file upload for proof
            $letterUrl = null;
            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $letterUrl = $file->storeAs('attendance_proofs', $filename, 'public');
            }
            
            // Create or update attendance excuse record with approved status
            \App\Models\AttendanceExcuse::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'presence_sessions_id' => $presenceSessionId,
                ],
                [
                    'detail' => $request->description ?? ($status === 'sick' ? 'Sakit' : 'Izin'),
                    'letter_url' => $letterUrl,
                    'status' => 'approved', // Automatically approve since PIC is creating it
                ]
            );
        }
        // If previously sick/excused but now something else (like absent), delete the excuse record
        else if ($previousStatus === 'sick' || $previousStatus === 'excused') {
            \App\Models\AttendanceExcuse::where('student_id', $studentId)
                ->where('presence_sessions_id', $presenceSessionId)
                ->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
        ]);
    }

    /**
     * Helper method to get or create a presence session ID
     * 
     * @param int $studentId
     * @param string $date
     * @return int
     */
    private function getOrCreatePresenceSessionId($studentId, $date)
    {
        // Try to find the existing presence session for this student's class on this date
        $student = Student::with('internshipClass.schedules')->findOrFail($studentId);
        $classId = $student->internship_class_id;
        
        // Find schedule for this class
        $schedule = Schedule::where('internship_class_id', $classId)->first();
        
        if (!$schedule) {
            // If no schedule exists, create a default one
            $schedule = Schedule::create([
                'internship_class_id' => $classId,
                'stase_id' => 1, // Default stase ID, replace with actual logic if needed
                'date' => $date,
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
            ]);
        }
        
        // Find or create presence session for this schedule and date
        $presenceSession = \App\Models\PresenceSession::firstOrCreate(
            [
                'schedule_id' => $schedule->id,
                'date' => $date,
            ],
            [
                'token' => \Illuminate\Support\Str::random(10),
                'start_time' => $schedule->start_time ?? '08:00:00',
                'end_time' => $schedule->end_time ?? '16:00:00',
            ]
        );
        
        return $presenceSession->id;
    }
}