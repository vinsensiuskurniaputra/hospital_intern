@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6 space-y-6">


        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Students Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">2,450</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-mortarboard text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Tahun 2024</p>
            </div>

            <!-- Doctors Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Dokter</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">126</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-file-earmark-person text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Tahun 2024</p>
            </div>

            <!-- Admins Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Admin</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">8</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-shield-check text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Tahun 2024</p>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Attendance Chart -->
                <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Student Attendance</h3>
                            <p class="text-sm text-gray-500">January - July 2024</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-800">1,000</p>
                            <p class="text-sm text-red-500 flex items-center justify-end">
                                <i class="bi bi-arrow-down-right"></i>
                                <span class="ml-1">-2.5%</span>
                            </p>
                        </div>
                    </div>
                    <div class="h-[300px]">
                        <!-- Add your chart component here -->
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <!-- Upcoming Schedule -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Upcoming Schedule</h3>
                        <a href="#" class="text-sm text-[#637F26] hover:text-[#85A832]">View All</a>
                    </div>
                    <div class="space-y-4">
                        @php
                            $schedules = [
                                [
                                    'course' => 'FK-101',
                                    'title' => 'Basic Surgery',
                                    'time' => '09:00 - 11:00',
                                    'dept' => 'Surgery',
                                ],
                                [
                                    'course' => 'FK-203',
                                    'title' => 'Clinical Practice',
                                    'time' => '13:00 - 15:00',
                                    'dept' => 'Internal Medicine',
                                ],
                            ];
                        @endphp

                        @foreach ($schedules as $schedule)
                            <div class="p-4 rounded-lg bg-[#F5F7F0] border border-[#637F26]/10">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span
                                            class="text-xs font-medium text-[#637F26] bg-white px-2 py-1 rounded">{{ $schedule['course'] }}</span>
                                        <h4 class="mt-2 font-medium text-gray-800">{{ $schedule['title'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $schedule['dept'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-600">{{ $schedule['time'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- User Activity Logs -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Recent Activity</h3>
                    <div class="space-y-4">
                        @php
                            $activities = [
                                [
                                    'admin' => 'John Doe',
                                    'action' => 'updated schedule for Class FK-01',
                                    'time' => '2 hours ago',
                                ],
                                [
                                    'admin' => 'Jane Smith',
                                    'action' => 'sent notification about schedule changes',
                                    'time' => '4 hours ago',
                                ],
                            ];
                        @endphp

                        @foreach ($activities as $activity)
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-2 rounded-full bg-[#637F26]"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-gray-800">{{ $activity['admin'] }}</span>
                                        {{ $activity['action'] }}
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Task Management -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Pending Tasks</h3>
                    <div class="space-y-4">
                        @php
                            $tasks = [
                                ['title' => 'Approve schedule changes', 'status' => 'pending', 'deadline' => 'Today'],
                                [
                                    'title' => 'Review student applications',
                                    'status' => 'pending',
                                    'deadline' => 'Tomorrow',
                                ],
                            ];
                        @endphp

                        @foreach ($tasks as $task)
                            <div class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                <input type="checkbox"
                                    class="w-4 h-4 rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $task['title'] }}</p>
                                    <p class="text-xs text-gray-500">Due: {{ $task['deadline'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Important Notifications -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Important Notifications</h3>
                    <div class="space-y-4">
                        @php
                            $notifications = [
                                [
                                    'title' => 'Schedule Change',
                                    'desc' => 'FK-101 class moved to Room 301',
                                    'time' => '1 hour ago',
                                ],
                                [
                                    'title' => 'System Update',
                                    'desc' => 'Maintenance scheduled for tonight',
                                    'time' => '2 hours ago',
                                ],
                            ];
                        @endphp

                        @foreach ($notifications as $notification)
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-800">{{ $notification['title'] }}</h4>
                                    <span class="text-xs text-gray-500">{{ $notification['time'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $notification['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Students -->
                <div class="lg:col-span-1 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Top Performing Students</h3>
                    </div>
                    <div class="space-y-4">
                        @php
                            $topStudents = [
                                ['name' => 'Alice Johnson', 'score' => '98', 'avatar' => 'AJ'],
                                ['name' => 'Bob Smith', 'score' => '96', 'avatar' => 'BS'],
                                ['name' => 'Carol White', 'score' => '95', 'avatar' => 'CW'],
                                ['name' => 'David Brown', 'score' => '94', 'avatar' => 'DB'],
                                ['name' => 'Eva Davis', 'score' => '93', 'avatar' => 'ED'],
                            ];
                        @endphp

                        @foreach ($topStudents as $index => $student)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                <span class="text-sm text-gray-500 w-6">{{ $index + 1 }}</span>
                                <div
                                    class="w-8 h-8 rounded-full bg-[#F5F7F0] text-[#637F26] flex items-center justify-center text-sm font-medium">
                                    {{ $student['avatar'] }}
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-800 flex-1">{{ $student['name'] }}</span>
                                <span class="text-sm font-semibold text-[#637F26]">{{ $student['score'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sample chart data
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Attendance',
                        data: [1000, 950, 900, 950, 925, 975, 925],
                        borderColor: '#637F26',
                        backgroundColor: '#F5F7F0',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
