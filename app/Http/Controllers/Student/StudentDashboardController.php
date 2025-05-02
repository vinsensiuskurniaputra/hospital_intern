<?php
// filepath: d:\Folder Utama\Documents\Kuliah\Semester 4\PBL\hospital_intern\app\Http\Controllers\Student\StudentDashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Presence;
use App\Models\Schedule;
use App\Models\StudentGrade;
use App\Models\Notification;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index()
    {
        try {
            // Dapatkan data student berdasarkan user yang login
            $student = Student::where('user_id', Auth::id())->with('user')->first();
            
            // Default values jika terjadi error atau data kosong
            $attendanceStats = [
                'total' => 0,
                'present' => ['count' => 0, 'percent' => 0],
                'sick' => ['count' => 0, 'percent' => 0],
                'absent' => ['count' => 0, 'percent' => 0]
            ];
            
            $todaySchedules = collect([]);
            $notifications = collect([]);
            $recentGrades = collect([]);
            
            if ($student) {
                // Statistik Kehadiran
                try {
                    $presences = Presence::where('student_id', $student->id)->get();
                    $totalPresences = $presences->count();
                    $presentCount = $presences->where('status', 'present')->count();
                    $sickCount = $presences->where('status', 'sick')->count();
                    $absentCount = $presences->where('status', 'absent')->count();
                    
                    $attendanceStats = [
                        'total' => $totalPresences,
                        'present' => [
                            'count' => $presentCount,
                            'percent' => $totalPresences > 0 ? round(($presentCount / $totalPresences) * 100, 1) : 0
                        ],
                        'sick' => [
                            'count' => $sickCount,
                            'percent' => $totalPresences > 0 ? round(($sickCount / $totalPresences) * 100, 1) : 0
                        ],
                        'absent' => [
                            'count' => $absentCount,
                            'percent' => $totalPresences > 0 ? round(($absentCount / $totalPresences) * 100, 1) : 0
                        ]
                    ];
                } catch (\Exception $e) {
                    Log::error('Error loading attendance stats: ' . $e->getMessage());
                }
                
                // Jadwal Hari Ini - tanpa waktu mulai/berakhir
                try {
                    $today = Carbon::now()->format('Y-m-d');
                    
                    // Cek apakah tabel Schedule ada
                    if (Schema::hasTable('schedules')) {
                        // Berdasarkan informasi, kolom tanggal bisa bernama 'date' atau 'date_schedule'
                        // Cek kedua kolom untuk memastikan fleksibilitas
                        $dateColumn = Schema::hasColumn('schedules', 'date') ? 'date' : 
                                     (Schema::hasColumn('schedules', 'date_schedule') ? 'date_schedule' : 'date');
                        
                        $todaySchedules = Schedule::whereDate($dateColumn, $today)
                            ->with(['stase'])
                            ->orderBy('id')
                            ->get();
                            
                        // Jika kosong, buat data dummy
                        if ($todaySchedules->isEmpty()) {
                            $todaySchedules = collect([
                                (object)[
                                    'stase' => (object)['name' => 'Tidak ada jadwal hari ini']
                                ]
                            ]);
                        }
                    } else {
                        // Tabel belum ada, buat data dummy
                        $todaySchedules = collect([
                            (object)[
                                'stase' => (object)['name' => 'Jadwal belum tersedia']
                            ]
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error loading schedules: ' . $e->getMessage());
                    $todaySchedules = collect([]);
                }
                
                // Notifikasi - gunakan model Notification
                try {
                    $notifications = Notification::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get()
                        ->map(function($notification) {
                            return [
                                'id' => $notification->id,
                                'title' => $notification->title,
                                'message' => $notification->message,
                                'created_at' => $notification->created_at,
                                'is_read' => $notification->is_read,
                                'type' => $notification->type ?? 'info',
                                'icon' => $notification->icon ?? 'bi bi-bell'
                            ];
                        });
                } catch (\Exception $e) {
                    Log::error('Error loading notifications: ' . $e->getMessage());
                    $notifications = collect([]);
                }
                
                // Nilai Terbaru
                try {
                    $recentGrades = StudentGrade::where('student_id', $student->id)
                        ->with(['departement', 'stase'])
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                } catch (\Exception $e) {
                    Log::error('Error loading grades: ' . $e->getMessage());
                    $recentGrades = collect([]);
                }
            }
            
            return view('pages.student.dashboard.index', [
                'attendanceStats' => $attendanceStats,
                'todaySchedules' => $todaySchedules,
                'notifications' => $notifications,
                'recentGrades' => $recentGrades,
                'student' => $student
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Return view with empty data if something went wrong
            return view('pages.student.dashboard.index', [
                'attendanceStats' => [
                    'total' => 0,
                    'present' => ['count' => 0, 'percent' => 0],
                    'sick' => ['count' => 0, 'percent' => 0],
                    'absent' => ['count' => 0, 'percent' => 0]
                ],
                'todaySchedules' => collect([]),
                'notifications' => collect([]),
                'recentGrades' => collect([]),
                'student' => null
            ])->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
}