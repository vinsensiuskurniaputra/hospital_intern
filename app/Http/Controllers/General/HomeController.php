<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Notification;
use App\Models\Presence;
use App\Models\Schedule;
use App\Models\StudentGrade;
use App\Models\ResponsibleUser;
use App\Models\Stase;
use App\Models\PresenceSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            $role = $user->roles()->first();
            
            if (!$role) {
                Log::warning('User without role tried to access the dashboard: ' . $user->id);
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki hak akses yang cukup');
            }
            
            Log::info('User login with role: ' . $role->name);
            
            // Perbaikan: Menangani role "pic" (bukan "responsible")
            switch ($role->name) {
                case 'student':
                    return $this->studentDashboard();
                
                case 'pic':  // Ganti dari 'responsible' ke 'pic'
                    return $this->picDashboard();
                
                case 'admin':
                    return $this->adminDashboard();
                
                default:
                    Log::warning('User with unknown role: ' . $role->name);
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki hak akses yang cukup');
            }
            
        } catch (\Exception $e) {
            Log::error('Error in HomeController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            Auth::logout();
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memuat halaman');
        }
    }

    private function adminDashboard()
    {
        // Data minimal untuk admin dashboard
        return view('pages.admin.dashboard.index', [
            'stats' => [
                'students' => Student::count(),
                'schedules' => Schedule::count(),
                'presences' => Presence::count()
            ]
        ]);
    }
    
    // Ganti method nama:
    private function picDashboard()
    {
        try {
            // Mendapatkan ID pengguna saat ini
            $userId = Auth::id();
            
            // Mengambil data responsible user
            $responsible = ResponsibleUser::where('user_id', $userId)->first();
            
            if (!$responsible) {
                Log::warning('PIC user not found for user_id: ' . $userId);
                return view('pages.responsible.dashboard.index', [
                    'error' => 'Data penanggung jawab tidak ditemukan',
                    'studentCount' => 0,
                    'todaySchedules' => collect([]),
                    'notifications' => collect([]),
                    'chartData' => ['labels' => [], 'data' => []],
                    'studentsToGrade' => collect([])
                ]);
            }
            
            // Mengambil stase yang dipegang oleh PIC ini
            $staseIds = Stase::where('responsible_user_id', $userId)->pluck('id')->toArray();
            
            if (empty($staseIds)) {
                Log::info('PIC user does not have any stase: ' . $userId);
                // Jika tidak ada stase, gunakan semua stase yang ada di database
                $staseIds = Stase::pluck('id')->toArray();
                if (empty($staseIds)) {
                    // Jika stase benar-benar kosong, buat nilai default
                    $staseIds = [0]; // ID yang tidak ada
                }
            }
            
            // 1. Data jadwal hari ini (dari stase yang dipegang PIC)
            $today = Carbon::now()->format('Y-m-d');
            $todaySchedules = Schedule::whereIn('stase_id', $staseIds)
                ->where(function($query) use ($today) {
                    $query->whereDate('date_schedule', $today)
                          ->orWhere(function($q) use ($today) {
                              $q->where('start_date', '<=', $today)
                                ->where('end_date', '>=', $today);
                          });
                })
                ->with(['stase', 'internshipClass'])
                ->take(6)
                ->get();
                
            // 2. Mahasiswa yang dibimbing (menghitung total)
            $studentCount = Student::whereHas('schedules', function($query) use ($staseIds) {
                $query->whereIn('stase_id', $staseIds);
            })->count();
            
            // 3. Data notifikasi terbaru untuk PIC
            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            
            // 4. Data untuk chart kehadiran
            $chartData = $this->getAttendanceChartData($staseIds);
            
            // 5. Data mahasiswa yang harus dinilai
            $studentsToGrade = Student::whereDoesntHave('grades', function($query) use ($staseIds) {
                $query->whereIn('stase_id', $staseIds);
            })
            ->whereHas('schedules', function($query) use ($staseIds, $today) {
                $query->whereIn('stase_id', $staseIds)
                    ->where('end_date', '<', $today);
            })
            ->with('user')
            ->take(5)
            ->get();
            
            return view('pages.responsible.dashboard.index', [
                'responsible' => $responsible,
                'todaySchedules' => $todaySchedules,
                'studentCount' => $studentCount,
                'notifications' => $notifications,
                'chartData' => $chartData,
                'studentsToGrade' => $studentsToGrade
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading PIC dashboard: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return view('pages.responsible.dashboard.index', [
                'error' => 'Terjadi kesalahan saat memuat dashboard: ' . $e->getMessage(),
                'studentCount' => 0,
                'todaySchedules' => collect([]),
                'notifications' => collect([]),
                'chartData' => ['labels' => [], 'data' => []],
                'studentsToGrade' => collect([])
            ]);
        }
    }

    // Helper method untuk chart kehadiran
    private function getAttendanceChartData($staseIds)
    {
        // Mengambil data 7 bulan terakhir untuk chart
        $months = [];
        $attendanceData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $months[] = $monthName;
            
            // Menghitung kehadiran untuk bulan tersebut
            $startOfMonth = $month->copy()->startOfMonth()->format('Y-m-d');
            $endOfMonth = $month->copy()->endOfMonth()->format('Y-m-d');
            
            // Hitung semua kehadiran pada stase yang dipegang PIC untuk bulan ini
            $attendanceCount = Presence::whereHas('presenceSession.schedule', function($query) use ($staseIds) {
                    $query->whereIn('stase_id', $staseIds);
                })
                ->whereDate('date_entry', '>=', $startOfMonth)
                ->whereDate('date_entry', '<=', $endOfMonth)
                ->where('status', 'present')
                ->count();
                
            $attendanceData[] = $attendanceCount;
        }
        
        return [
            'labels' => $months,
            'data' => $attendanceData
        ];
    }
    
    private function studentDashboard()
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
                
                // Notifikasi
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