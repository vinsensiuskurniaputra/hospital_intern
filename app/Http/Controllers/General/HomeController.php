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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
            // Get logged in user
            $user = Auth::user();
            $userId = Auth::id();
            
            // Get responsible user data
            $responsible = ResponsibleUser::where('user_id', $userId)->first();

            // Default values if error or empty data
            $todaySchedules = collect([]);
            $recentAttendances = collect([]);
            $notifications = collect([]);
            $chartData = ['labels' => [], 'data' => []];
            $studentCount = 0;
            
            if ($responsible) {
                // Dapatkan stase IDs dari tabel pivot
                $staseIds = DB::table('responsible_stase')
                    ->where('responsible_user_id', $responsible->id)
                    ->pluck('stase_id')
                    ->toArray();

                if (!empty($staseIds)) {
                    // 1. JADWAL HARI INI
                    try {
                        $today = Carbon::now()->format('Y-m-d');
                        
                        // Dapatkan jadwal terkait stase tersebut hari ini
                        $scheduleIds = Schedule::whereIn('stase_id', $staseIds)
                            ->where('start_date', '<=', $today)
                            ->where('end_date', '>=', $today)
                            ->pluck('id')
                            ->unique()
                            ->values()
                            ->toArray();
                        
                        // Include classYear relationship
                        $todaySchedules = Schedule::whereIn('id', $scheduleIds)
                            ->with([
                                'stase', 
                                'internshipClass.classYear'
                            ])
                            ->take(6)
                            ->get();
                    } catch (\Exception $e) {
                        Log::error('Error loading schedules: ' . $e->getMessage());
                        $todaySchedules = collect([]);
                    }

                    // 2. MAHASISWA YANG DIBIMBING
                    try {
                        // Hitung jumlah mahasiswa dari semua kelas yang ada di stase yang dipegang PIC
                        $internshipClassIds = Schedule::whereIn('stase_id', $staseIds)
                            ->distinct()
                            ->pluck('internship_class_id')
                            ->toArray();
                        
                        $studentCount = Student::whereIn('internship_class_id', $internshipClassIds)
                            ->count();
                            
                        Log::info('Student count calculation', [
                            'stase_ids' => $staseIds,
                            'internship_class_ids' => $internshipClassIds,
                            'student_count' => $studentCount
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error calculating student count: ' . $e->getMessage());
                        $studentCount = 0;
                    }

                    // 3. CHART DATA KEHADIRAN MAHASISWA
                    try {
                        $chartData = $this->getAttendanceChartData($staseIds);
                    } catch (\Exception $e) {
                        Log::error('Error loading chart data: ' . $e->getMessage());
                        $chartData = $this->getDefaultChartData();
                    }
                } else {
                    // Jika PIC tidak memiliki stase, set default values
                    $todaySchedules = collect([]);
                    $studentCount = 0;
                    $chartData = $this->getDefaultChartData();
                    
                    Log::warning('PIC has no assigned stase', ['responsible_id' => $responsible->id]);
                }
                
                // 4. NOTIFIKASI
                try {
                    $notifications = Notification::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                } catch (\Exception $e) {
                    Log::error('Error loading notifications: ' . $e->getMessage());
                    $notifications = collect([]);
                }
            }
            
            return view('pages.responsible.dashboard.index', compact(
                'todaySchedules', 
                'recentAttendances',
                'notifications',
                'chartData',
                'studentCount'
            ));
        } catch (\Exception $e) {
            Log::error('Error in picDashboard: ' . $e->getMessage());
            
            return view('pages.responsible.dashboard.index', [
                'todaySchedules' => collect([]),
                'recentAttendances' => collect([]),
                'notifications' => collect([]),
                'chartData' => $this->getDefaultChartData(),
                'studentCount' => 0
            ])->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    // Helper method untuk chart kehadiran - PERBAIKAN: Januari sampai bulan saat ini
    private function getAttendanceChartData($staseIds)
    {
        try {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month; // 1-12
            $months = [];
            $attendanceData = [];
            
            // PERBAIKAN: Loop dari Januari (bulan 1) sampai bulan saat ini
            for ($month = 1; $month <= $currentMonth; $month++) {
                $monthCarbon = Carbon::create($currentYear, $month, 1);
                $monthName = $monthCarbon->format('M'); // Jan, Feb, Mar, ...
                $months[] = $monthName;
                
                // Menghitung kehadiran untuk bulan tersebut
                $startOfMonth = $monthCarbon->startOfMonth()->format('Y-m-d');
                $endOfMonth = $monthCarbon->copy()->endOfMonth()->format('Y-m-d');
                
                // Hitung kehadiran mahasiswa pada stase yang dipegang PIC untuk bulan ini
                $attendanceCount = Presence::whereHas('presenceSession.schedule', function($query) use ($staseIds) {
                        $query->whereIn('stase_id', $staseIds);
                    })
                    ->whereDate('date_entry', '>=', $startOfMonth)
                    ->whereDate('date_entry', '<=', $endOfMonth)
                    ->where('status', 'present')
                    ->count();
                    
                $attendanceData[] = $attendanceCount;
            }
            
            Log::info('Chart data generated for year ' . $currentYear . ' (Jan - ' . Carbon::now()->format('M') . ')', [
                'stase_ids' => $staseIds,
                'months' => $months,
                'attendance_data' => $attendanceData,
                'total_attendance' => array_sum($attendanceData),
                'year' => $currentYear,
                'current_month' => $currentMonth
            ]);
            
            return [
                'labels' => $months,
                'data' => $attendanceData,
                'current_month_index' => $currentMonth - 1 // 0-based index untuk JavaScript
            ];
        } catch (\Exception $e) {
            Log::error('Error in getAttendanceChartData: ' . $e->getMessage());
            return $this->getDefaultChartData();
        }
    }

    // Helper method untuk default chart data - PERBAIKAN: Hanya sampai bulan saat ini
    private function getDefaultChartData()
    {
        $currentMonth = Carbon::now()->month;
        $months = [];
        
        // Hanya sampai bulan saat ini
        for ($month = 1; $month <= $currentMonth; $month++) {
            $months[] = Carbon::create(Carbon::now()->year, $month, 1)->format('M');
        }
        
        return [
            'labels' => $months,
            'data' => array_fill(0, count($months), 0),
            'current_month_index' => $currentMonth - 1
        ];
    }
    
    private function studentDashboard()
    {
        try {
            // Get logged in user
            $user = Auth::user();
            $userId = Auth::id();
            
            // Get student data
            $student = Student::where('user_id', $userId)->first();

            // Default values if error or empty data
            $attendanceStats = [
                'total' => 0,
                'present' => ['count' => 0, 'percent' => 0],
                'sick' => ['count' => 0, 'percent' => 0],
                'absent' => ['count' => 0, 'percent' => 0]
            ];
            
            $todaySchedules = collect([]);
            $notifications = collect([]);
            $recentGrades = collect([]);
            
            // Get hospital coordinates and radius from config (no defaults)
            $hospitalCoordinates = [
                'name' => config('hospital.name'),
                'latitude' => config('hospital.coordinates.latitude'),
                'longitude' => config('hospital.coordinates.longitude')
            ];
            
            // Get attendance radius from config (no default)
            $attendanceRadius = config('hospital.attendance_radius');
            
            // Validate that hospital configuration is set
            if (!$hospitalCoordinates['name'] || 
                !$hospitalCoordinates['latitude'] || 
                !$hospitalCoordinates['longitude'] || 
                !$attendanceRadius) {
                
                Log::error('Hospital configuration is not properly set in environment variables');
                return view('pages.student.dashboard.index', [
                    'attendanceStats' => $attendanceStats,
                    'todaySchedules' => collect([]),
                    'notifications' => collect([]),
                    'recentGrades' => collect([]),
                    'student' => null,
                    'hospitalCoordinates' => [
                        'name' => 'Hospital',
                        'latitude' => 0,
                        'longitude' => 0
                    ],
                    'attendanceRadius' => 500
                ])->with('error', 'Konfigurasi lokasi rumah sakit belum diatur. Silakan hubungi administrator.');
            }
            
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
                
                // Jadwal Hari Ini
                try {
                    $today = Carbon::now()->format('Y-m-d');
                    $internshipClassId = $student->internship_class_id;
                    
                    if (Schema::hasTable('schedules') && $internshipClassId) {
                        // Query dengan relationship yang benar
                        $todaySchedules = Schedule::where('internship_class_id', $internshipClassId)
                            ->where('start_date', '<=', $today)
                            ->where('end_date', '>=', $today)
                            ->with([
                                'stase.departement',
                                'internshipClass',
                                'responsibleAssignments.responsibleUser.user'
                            ])
                            ->orderBy('start_date')
                            ->get();
                    } else {
                        $todaySchedules = collect([]);
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
                        ->with(['stase'])
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                } catch (\Exception $e) {
                    Log::error('Error loading grades: ' . $e->getMessage());
                    $recentGrades = collect([]);
                }
            }
            
            return view('pages.student.dashboard.index', compact(
                'todaySchedules', 
                'student',
                'notifications',
                'recentGrades',
                'attendanceStats',
                'hospitalCoordinates',
                'attendanceRadius'
            ));
        } catch (\Exception $e) {
            Log::error('Error in studentDashboard: ' . $e->getMessage());
            
            // Error handling with validation check
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
                'student' => null,
                'hospitalCoordinates' => [
                    'name' => config('hospital.name') ?: 'Hospital',
                    'latitude' => config('hospital.coordinates.latitude') ?: 0,
                    'longitude' => config('hospital.coordinates.longitude') ?: 0
                ],
                'attendanceRadius' => config('hospital.attendance_radius') ?: 500
            ])->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
    
    /**
     * Generate token for attendance
     */
    public function generatePresenceToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'schedule_id' => 'required|exists:schedules,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ]);
            }

            // Cek apakah schedule dimiliki oleh PIC ini
            $userId = Auth::id();
            $schedule = Schedule::with(['stase', 'internshipClass'])->find($request->schedule_id);

            if (!$schedule) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ]);
            }

            // Dapatkan data responsible user berdasarkan user ID
            $responsible = ResponsibleUser::where('user_id', $userId)->first();
            
            if (!$responsible) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penanggung jawab tidak ditemukan'
                ]);
            }
            
            // Cek apakah PIC ini memiliki akses ke stase tersebut melalui tabel pivot
            $hasAccess = DB::table('responsible_stase')
                ->where('responsible_user_id', $responsible->id)
                ->where('stase_id', $schedule->stase_id)
                ->exists();
                
            if (!$hasAccess) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses untuk membuat kode presensi pada jadwal ini'
                ]);
            }

            // Cek apakah sudah ada session aktif untuk jadwal ini hari ini
            $today = Carbon::now()->format('Y-m-d');
            $existingSession = PresenceSession::where('schedule_id', $schedule->id)
                ->whereDate('date', $today)
                ->first();
                
            if ($existingSession) {
                // Cek apakah token masih berlaku
                $now = Carbon::now();
                $isExpired = false;
                
                if ($existingSession->expiration_time) {
                    if ($existingSession->expiration_time instanceof Carbon) {
                        $isExpired = $existingSession->expiration_time->lt($now);
                    } else {
                        $expiry = Carbon::parse($existingSession->expiration_time);
                        $isExpired = $expiry->lt($now);
                    }
                }
                
                if (!$isExpired) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sudah ada kode aktif untuk jadwal ini hari ini',
                        'data' => [
                            'token' => $existingSession->token,
                            'expiration_time' => $existingSession->expiration_time instanceof Carbon 
                                ? $existingSession->expiration_time->format('Y-m-d H:i:s')
                                : Carbon::parse($existingSession->expiration_time)->format('Y-m-d H:i:s'),
                            'schedule_name' => $schedule->internshipClass->name ?? 'Kelas',
                            'stase_name' => $schedule->stase->name ?? 'Stase',
                            'date' => $today
                        ]
                    ]);
                }
            }

            // Generate token unik
            $token = strtoupper(Str::random(6));
            
            // Atur masa berlaku sampai akhir hari ini (23:59:59)
            $expirationTime = Carbon::now()->endOfDay();

            if ($existingSession) {
                // Update session yang ada
                $existingSession->update([
                    'token' => $token,
                    'expiration_time' => $expirationTime
                ]);
                $session = $existingSession;
            } else {
                // Buat session baru
                $session = PresenceSession::create([
                    'schedule_id' => $schedule->id,
                    'token' => $token,
                    'date' => $today,
                    'expiration_time' => $expirationTime
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Token berhasil dibuat',
                'data' => [
                    'token' => $token,
                    'expiration_time' => $expirationTime->format('Y-m-d H:i:s'),
                    'schedule_name' => $schedule->internshipClass->name ?? 'Kelas',
                    'stase_name' => $schedule->stase->name ?? 'Stase',
                    'date' => $today
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error generating token: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat membuat token: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get active tokens for the responsible user
     */
    public function getActiveTokens()
    {
        try {
            $userId = Auth::id();
            $today = Carbon::now()->format('Y-m-d');
            
            // Dapatkan responsible user
            $responsible = ResponsibleUser::where('user_id', $userId)->first();
            
            if (!$responsible) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penanggung jawab tidak ditemukan'
                ]);
            }
            
            // Dapatkan stase IDs dari tabel pivot
            $staseIds = DB::table('responsible_stase')
                ->where('responsible_user_id', $responsible->id)
                ->pluck('stase_id')
                ->toArray();
    
            // Jika tidak ada stase, kembalikan array kosong
            if (empty($staseIds)) {
                return response()->json([
                    'status' => true,
                    'data' => [
                        'active_tokens' => [],
                        'schedule_options' => []
                    ]
                ]);
            }
            
            // Dapatkan semua jadwal terkait stase tersebut hari ini
            $scheduleIds = Schedule::whereIn('stase_id', $staseIds)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->pluck('id')
                ->unique()
                ->values()
                ->toArray();
            
            // PERBAIKAN: Include classYear relationship
            $schedules = Schedule::whereIn('id', $scheduleIds)
                ->with([
                    'stase', 
                    'internshipClass.classYear' // Include classYear relationship
                ])
                ->get();
            
            // Dapatkan semua sesi presensi aktif untuk jadwal tersebut
            $now = Carbon::now();
            $activeSessions = collect();
            $activeScheduleIds = []; // Track schedules that already have active tokens
            
            foreach ($schedules as $schedule) {
                $session = PresenceSession::where('schedule_id', $schedule->id)
                    ->whereDate('date', $today)
                    ->where(function($query) use ($now) {
                        $query->whereNull('expiration_time')
                            ->orWhere('expiration_time', '>', $now);
                    })
                    ->first();
                    
                if ($session) {
                    $activeScheduleIds[] = $schedule->id;
                    
                    // Ensure expiration_time is properly processed
                    $expirationTime = null;
                    if ($session->expiration_time) {
                        // If it's already a Carbon instance
                        if ($session->expiration_time instanceof Carbon) {
                            $expirationTime = $session->expiration_time->format('Y-m-d H:i:s');
                        } 
                        // If it's a string, try to parse it
                        else {
                            $expirationTime = Carbon::parse($session->expiration_time)->format('Y-m-d H:i:s');
                        }
                    }
                    
                    // PERBAIKAN: Include class year in schedule name
                    $className = $schedule->internshipClass->name ?? 'Kelas';
                    $classYear = $schedule->internshipClass->classYear->class_year ?? '';
                    $staseName = $schedule->stase->name ?? 'Stase';
                    
                    $scheduleDisplayName = $className;
                    if ($classYear) {
                        $scheduleDisplayName .= " ({$classYear})";
                    }
                    $scheduleDisplayName .= " - {$staseName}";
                    
                    $activeSessions->push([
                        'id' => $session->id,
                        'token' => $session->token,
                        'schedule_id' => $schedule->id,
                        'schedule_name' => $scheduleDisplayName,
                        'stase_name' => $staseName,
                        'expiration_time' => $expirationTime,
                        'created_at' => $session->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            }
            
            // PERBAIKAN: Format schedule options dengan class year
            $scheduleOptions = $schedules->map(function($schedule) use ($activeScheduleIds) {
                $className = $schedule->internshipClass->name ?? 'Kelas';
                $classYear = $schedule->internshipClass->classYear->class_year ?? '';
                $staseName = $schedule->stase->name ?? 'Stase';
                
                // Format nama dengan class year
                $displayName = $className;
                if ($classYear) {
                    $displayName .= " ({$classYear})";
                }
                $displayName .= " - {$staseName}";
                
                return [
                    'id' => $schedule->id,
                    'name' => $displayName,
                    'has_active_token' => in_array($schedule->id, $activeScheduleIds)
                ];
            })->toArray();
            
            return response()->json([
                'status' => true,
                'data' => [
                    'active_tokens' => $activeSessions,
                    'schedule_options' => $scheduleOptions
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error in getActiveTokens: " . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage(),
                'data' => [
                    'active_tokens' => [],
                    'schedule_options' => []
                ]
            ]);
        }
    }
    
    /**
     * Student submits attendance token
     */
    public function submitAttendanceToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'device_info' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ]);
            }

            // Cari token di database
            $token = strtoupper($request->token);
            $now = Carbon::now();
            $today = $now->format('Y-m-d');
            
            $session = PresenceSession::where('token', $token)
                ->whereDate('date', $today)
                ->where(function($query) use ($now) {
                    $query->whereNull('expiration_time')
                          ->orWhere('expiration_time', '>=', $now);
                })
                ->first();
                
            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token tidak valid atau sudah kadaluwarsa'
                ]);
            }
            
            // Dapatkan data mahasiswa
            $student = Student::where('user_id', Auth::id())->first();
            
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ]);
            }
            
            // Cek apakah mahasiswa terdaftar di kelas tersebut
            $schedule = $session->schedule;
            
            // Validasi apakah mahasiswa terdaftar di jadwal ini
            $isRegistered = false;
            
            // Cek dari relasi schedule->internshipClass->students
            if ($schedule && $schedule->internshipClass) {
                $isRegistered = DB::table('students')
                    ->where('students.id', $student->id)
                    ->where('students.internship_class_id', $schedule->internshipClass->id)
                    ->exists();
            }
            
            // Jika tidak terdaftar dan tidak ada exception mode, tolak
            if (!$isRegistered && !config('presence.allow_any_student_to_any_schedule', false)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak terdaftar pada jadwal ini'
                ]);
            }
            
            // Cek apakah student sudah presensi hari ini
            $existingPresence = Presence::where('student_id', $student->id)
                ->whereHas('presenceSession', function($query) use ($today) {
                    $query->whereDate('date', $today);
                })
                ->exists();
                
            if ($existingPresence) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda sudah melakukan presensi hari ini'
                ]);
            }

            // Check if hospital configuration is set
            $hospitalLatitude = config('hospital.coordinates.latitude');
            $hospitalLongitude = config('hospital.coordinates.longitude');
            $maxRadius = config('hospital.attendance_radius');
            
            if (!$hospitalLatitude || !$hospitalLongitude || !$maxRadius) {
                return response()->json([
                    'status' => false,
                    'message' => 'Konfigurasi lokasi rumah sakit belum diatur. Silakan hubungi administrator.'
                ]);
            }
            
            // Periksa jika mahasiswa berada dalam radius yang diperbolehkan
            $distance = $this->calculateDistance(
                $request->latitude, 
                $request->longitude, 
                $hospitalLatitude, 
                $hospitalLongitude
            );
            
            if ($distance > $maxRadius) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda berada di luar radius presensi yang diizinkan',
                    'data' => [
                        'distance' => round($distance),
                        'max_radius' => $maxRadius
                    ]
                ]);
            }
            
            // Catat presensi
            $presence = Presence::create([
                'student_id' => $student->id,
                'presence_sessions_id' => $session->id,
                'date_entry' => $today,
                'check_in' => now()->format('H:i:s'),
                'check_out' => '00:00:00', // Gunakan '00:00:00' sebagai placeholder
                'status' => 'present',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'device_info' => $request->device_info ?? 'Web Browser'
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Presensi berhasil',
                'data' => [
                    'presence_id' => $presence->id,
                    'date' => $today,
                    'check_in_time' => $presence->check_in,
                    'token' => $token,
                    'schedule' => [
                        'class' => $schedule->internshipClass->name ?? 'Kelas',
                        'stase' => $schedule->stase->name ?? 'Stase'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in submitAttendanceToken: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Student checkout attendance
     */
    public function checkoutAttendance(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'device_info' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ]);
            }

            // Dapatkan data mahasiswa
            $student = Student::where('user_id', Auth::id())->first();
            
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ]);
            }
            
            // Cari presensi hari ini yang belum checkout
            $today = Carbon::now()->format('Y-m-d');
            $presence = Presence::where('student_id', $student->id)
                ->whereDate('date_entry', $today)
                ->where(function($query) {
                    $query->whereNull('check_out')
                          ->orWhere('check_out', '00:00:00'); // Tambahkan kondisi untuk mengenali '00:00:00'
                })
                ->first();
                
            if (!$presence) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ditemukan presensi yang perlu checkout hari ini'
                ]);
            }
            
            // Check if hospital configuration is set
            $hospitalLatitude = config('hospital.coordinates.latitude');
            $hospitalLongitude = config('hospital.coordinates.longitude');
            $maxRadius = config('hospital.attendance_radius');
            
            if (!$hospitalLatitude || !$hospitalLongitude || !$maxRadius) {
                return response()->json([
                    'status' => false,
                    'message' => 'Konfigurasi lokasi rumah sakit belum diatur. Silakan hubungi administrator.'
                ]);
            }
            
            $distance = $this->calculateDistance(
                $request->latitude, 
                $request->longitude, 
                $hospitalLatitude, 
                $hospitalLongitude
            );
            
            if ($distance > $maxRadius) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda berada di luar radius presensi yang diizinkan',
                    'data' => [
                        'distance' => round($distance),
                        'max_radius' => $maxRadius
                    ]
                ]);
            }

            // Update checkout time
            $presence->check_out = now()->format('H:i:s');
            $presence->save();
            
            // Tambahkan relasi untuk mendapatkan informasi jadwal
            $presenceWithSession = Presence::with('presenceSession.schedule.stase', 'presenceSession.schedule.internshipClass')
                ->find($presence->id);
            
            $scheduleName = '';
            if ($presenceWithSession->presenceSession && $presenceWithSession->presenceSession->schedule) {
                $schedule = $presenceWithSession->presenceSession->schedule;
                $scheduleName = ($schedule->stase->name ?? 'Stase') . ' - ' . 
                                ($schedule->internshipClass->name ?? 'Kelas');
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Checkout berhasil',
                'data' => [
                    'presence_id' => $presence->id,
                    'check_in' => $presence->check_in,
                    'check_out' => $presence->check_out,
                    'schedule' => $scheduleName
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkoutAttendance: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check if student has attendance today
     */
    public function checkTodayAttendance()
    {
        $userId = Auth::id();
        $student = Student::where('user_id', $userId)->first();
        
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        $today = Carbon::now()->format('Y-m-d');
        
        // Cek apakah sudah ada presensi hari ini
        $todayPresence = Presence::where('student_id', $student->id)
            ->whereDate('date_entry', $today)
            ->with('presenceSession.schedule.stase', 'presenceSession.schedule.internshipClass')
            ->first();
            
        if ($todayPresence) {
            $scheduleName = '';
            $token = '';
            if ($todayPresence->presenceSession && $todayPresence->presenceSession->schedule) {
                $schedule = $todayPresence->presenceSession->schedule;
                $staseName = $schedule->stase->name ?? 'Stase';
                $className = $schedule->internshipClass->name ?? 'Kelas';
                $scheduleName = "$staseName - $className";
                $token = $todayPresence->presenceSession->token ?? '';
            }
            
            // PERBAIKAN: Logika untuk menentukan apakah perlu checkout
            $needsCheckout = false;
            $checkOutValue = $todayPresence->check_out;
            
            // Check out diperlukan jika:
            // 1. check_out adalah null, ATAU
            // 2. check_out adalah '00:00:00' (placeholder value)
            if ($checkOutValue === null || $checkOutValue === '00:00:00') {
                $needsCheckout = true;
            }
            
            return response()->json([
                'status' => true,
                'data' => [
                    'has_attendance' => true,
                    'presence_id' => $todayPresence->id,
                    'check_in' => $todayPresence->check_in,
                    'check_out' => $todayPresence->check_out,
                    'needs_checkout' => $needsCheckout,
                    'token' => $token,
                    'schedule' => $scheduleName
                ]
            ]);
        }
        
        return response()->json([
            'status' => true,
            'data' => [
                'has_attendance' => false
            ]
        ]);
    }
    
    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Earth's radius in meters
        $radius = 6371000;
        
        // Distance in meters
        return $radius * $c;
    }
}