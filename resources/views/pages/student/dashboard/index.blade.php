@extends('layouts.auth')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-1">
            <!-- Quick Action Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Quick Action</h2>
                
                <!-- Presensi Section -->
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Presensi</h3>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-sm">Status:</span>
                        <span id="attendance-status" class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded">Belum Presensi</span>
                    </div>
                    
                    <div id="location-status" class="mb-3 text-sm">
                        <div class="flex items-center">
                            <span class="text-yellow-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mendeteksi lokasi...
                            </span>
                        </div>
                    </div>
                    
                    <div id="hospital-distance" class="mb-3 hidden">
                        <div class="text-xs">
                            <span class="font-medium">Jarak dari Lokasi:</span>
                            <span id="distance-value" class="ml-1">Menghitung...</span>
                        </div>
                        <div id="distance-status" class="text-xs mt-0.5"></div>
                    </div>
                    
                    <div class="relative mb-2">
                        <input type="text" id="attendance-token" placeholder="Masukkan Kode Presensi" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]"
                            maxlength="6">
                    </div>
                    <button id="submit-token-btn" class="w-full bg-[#637F26] hover:bg-[#4e6320] text-white font-medium py-2 px-4 rounded-lg transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                        Submit
                    </button>
                    
                    <div id="attendance-result" class="mt-3 hidden"></div>
                </div>
            </div>
        </div>
        
        <!-- Middle/Right Column -->
        <div class="lg:col-span-2">
            <!-- Attendance Statistics Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-2">Total Kehadiran</h2>
                <p class="text-gray-700 font-medium mb-4">{{ $attendanceStats['total'] ?? 0 }} Kehadiran</p>
                
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Pie Chart -->
                    <div class="w-48 h-48">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
                    <div class="mt-4 md:mt-0 md:ml-8 flex flex-col space-y-2 flex-1">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>Hadir</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['present']['percent'] ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                            <span>Izin</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['sick']['percent'] ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <span>Alpa</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['absent']['percent'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($todaySchedules as $schedule)
                    <div class="bg-gray-100 rounded-lg p-4 shadow-[0_2px_4px_-1px_rgba(0,0,0,0.1)]">
                        @if(isset($schedule->internshipClass))
                            <h3 class="font-medium mb-1">{{ $schedule->internshipClass->name ?? 'Kelas' }}</h3>
                        @endif
                        
                        <div class="text-sm text-gray-600 font-medium">
                            {{ $schedule->stase->name ?? 'Stase tidak tersedia' }}
                        </div>
                        
                        @if(isset($schedule->departement))
                            <div class="text-sm text-gray-500 mb-1">
                                {{ $schedule->departement->name ?? '' }}
                            </div>
                        @endif
                        
                        @if(isset($schedule->responsibleUser) && isset($schedule->responsibleUser->user))
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="bi bi-person-circle mr-1"></i>
                                <span>{{ $schedule->responsibleUser->user->name ?? '' }}</span>
                            </div>
                        @endif
                        
                        <!-- Tambahkan informasi periode jadwal -->
                        <div class="mt-2 text-xs text-gray-500">
                            <i class="bi bi-calendar-event mr-1"></i>
                            <span>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center p-6 text-gray-500">
                        <i class="bi bi-calendar-x text-3xl mb-2"></i>
                        <p>Tidak ada jadwal untuk hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Notifikasi Terbaru</h2>
                <a href="{{ route('student.notifications') }}" class="text-sm text-[#637F26] hover:underline">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($notifications as $notification)
                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0 {{ $notification['is_read'] ? 'opacity-70' : '' }}">
                    <h3 class="font-medium mb-1">{{ $notification['title'] }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $notification['message'] }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $notification['created_at']->format('d M Y - H:i') }}</p>
                </div>
                @empty
                <div class="text-center p-6 text-gray-500">
                    <i class="bi bi-bell-slash text-3xl mb-2"></i>
                    <p>Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Grade History -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Riwayat Penilaian</h2>
                <a href="{{ route('student.grades') }}" class="text-sm text-[#637F26] hover:underline">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentGrades as $grade)
                @php
                    $gradeClass = 'bg-yellow-500';
                    if ($grade->avg_grades >= 80) {
                        $gradeClass = 'bg-green-500';
                    } elseif ($grade->avg_grades < 60) {
                        $gradeClass = 'bg-red-500';
                    }
                @endphp
                
                <!-- Grade Item -->
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg shadow-[0_2px_4px_-1px_rgba(0,0,0,0.1)]">
                    <div>
                        <h3 class="font-medium">{{ $grade->departement->name ?? 'Departemen' }} - {{ $grade->stase->name ?? 'Stase' }}</h3>
                        <p class="text-sm text-gray-500">{{ $grade->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 {{ $gradeClass }} text-white text-2xl font-bold rounded">
                        {{ round($grade->avg_grades) }}
                    </div>
                </div>
                @empty
                <div class="text-center p-6 text-gray-500">
                    <i class="bi bi-card-list text-3xl mb-2"></i>
                    <p>Belum ada data penilaian</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Data dari backend - menyimpan data persentase untuk chart
        const attendancePercentData = @json([
            $attendanceStats['present']['percent'] ?? 0,
            $attendanceStats['sick']['percent'] ?? 0, 
            $attendanceStats['absent']['percent'] ?? 0
        ]);
        
        // Data absolut untuk ditampilkan di tooltip
        const attendanceCountData = @json([
            $attendanceStats['present']['count'] ?? 0,
            $attendanceStats['sick']['count'] ?? 0, 
            $attendanceStats['absent']['count'] ?? 0
        ]);
        
        // Labels untuk chart
        const labels = ['Hadir', 'Izin', 'Alpa'];
        
        // Membuat chart dengan Chart.js
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: attendancePercentData,
                    backgroundColor: [
                        '#4ADE80', // green
                        '#FBBF24', // yellow
                        '#F87171'  // red
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const label = labels[index];
                                const count = attendanceCountData[index];
                                const percent = attendancePercentData[index];
                                
                                return `${label}: ${count} presensi (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if hospital configuration is properly set
        @if(!config('hospital.name') || !config('hospital.coordinates.latitude') || !config('hospital.coordinates.longitude') || !config('hospital.attendance_radius'))
            // Show error message if hospital config is not set
            document.getElementById('attendance-result').classList.remove('hidden');
            document.getElementById('attendance-result').innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded p-3">
                    <p class="text-red-700 font-medium">Konfigurasi Error</p>
                    <p class="text-sm text-red-600">Konfigurasi lokasi rumah sakit belum diatur. Silakan hubungi administrator.</p>
                </div>
            `;
            
            // Disable attendance functionality
            document.getElementById('attendance-token').disabled = true;
            document.getElementById('submit-token-btn').disabled = true;
            return;
        @endif
        
        // Attendance functionality
        const tokenInput = document.getElementById('attendance-token');
        const submitBtn = document.getElementById('submit-token-btn');
        const attendanceStatus = document.getElementById('attendance-status');
        const locationStatus = document.getElementById('location-status');
        const hospitalDistance = document.getElementById('hospital-distance');
        const distanceValue = document.getElementById('distance-value');
        const distanceStatus = document.getElementById('distance-status');
        const attendanceResult = document.getElementById('attendance-result');
        
        // Location variables
        let currentLocation = null;
        
        // Hospital configuration from backend (now without defaults)
        const hospitalConfig = {
            name: "{{ config('hospital.name') }}",
            latitude: {{ config('hospital.coordinates.latitude') }},
            longitude: {{ config('hospital.coordinates.longitude') }},
            radius: {{ config('hospital.attendance_radius') }}
        };
        
        // Validate hospital config on frontend
        if (!hospitalConfig.name || !hospitalConfig.latitude || !hospitalConfig.longitude || !hospitalConfig.radius) {
            showError('Konfigurasi lokasi rumah sakit tidak lengkap');
            tokenInput.disabled = true;
            submitBtn.disabled = true;
            return;
        }
        
        // Helper function to show error messages
        function showError(message) {
            attendanceResult.classList.remove('hidden');
            attendanceResult.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded p-3">
                    <p class="text-red-700 font-medium">Error</p>
                    <p class="text-sm text-red-600">${message}</p>
                </div>
            `;
        }
        
        // Function to check today's attendance
        function checkTodayAttendance() {
            fetch('/attendance/check-today', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status && data.data.has_attendance) {
                    // Student has already done attendance today
                    const attendanceData = data.data;
                    
                    if (attendanceData.needs_checkout) {
                        // Needs checkout
                        attendanceStatus.textContent = 'Sudah Presensi';
                        attendanceStatus.classList.remove('bg-orange-100', 'text-orange-600');
                        attendanceStatus.classList.add('bg-green-100', 'text-green-600');
                        
                        attendanceResult.classList.remove('hidden');
                        attendanceResult.innerHTML = `
                            <div class="bg-green-50 border border-green-200 rounded p-3">
                                <p class="text-green-700 font-medium">Presensi berhasil!</p>
                                <p class="text-sm text-green-600">Jam masuk: ${attendanceData.check_in}</p>
                                <p class="text-xs text-green-600 mt-1">${attendanceData.schedule || 'Jadwal'}</p>
                                <div class="mt-1 mb-2">
                                    <p class="text-xs text-gray-500">Kode presensi: <span class="font-mono font-medium">${attendanceData.token || ''}</span></p>
                                </div>
                                <button id="checkout-btn" class="w-full bg-[#637F26] hover:bg-[#4e6320] text-white font-medium py-2 px-4 rounded-md text-sm">
                                    Presensi Pulang
                                </button>
                            </div>
                        `;
                        
                        // Attach event listener to checkout button
                        document.getElementById('checkout-btn').addEventListener('click', submitCheckout);
                    } else {
                        // Already checked out
                        attendanceStatus.textContent = 'Sudah Presensi Pulang';
                        attendanceStatus.classList.remove('bg-orange-100', 'text-orange-600', 'bg-green-100', 'text-green-600');
                        attendanceStatus.classList.add('bg-red-100', 'text-red-600');
                        
                        attendanceResult.classList.remove('hidden');
                        attendanceResult.innerHTML = `
                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <p class="text-blue-700 font-medium">Presensi hari ini sudah selesai</p>
                                <p class="text-sm text-blue-600">Jam masuk: ${attendanceData.check_in}</p>
                                <p class="text-sm text-blue-600">Jam pulang: ${attendanceData.check_out}</p>
                                <p class="text-xs text-blue-600 mt-1">${attendanceData.schedule || 'Jadwal'}</p>
                            </div>
                        `;
                    }
                    
                    // Disable input and button
                    tokenInput.disabled = true;
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error checking today attendance:', error);
            });
        }
        
        // Function to submit checkout
        function submitCheckout() {
            if (!currentLocation) {
                getLocation();
                showError('Menunggu data lokasi...');
                return;
            }
            
            const checkoutBtn = document.getElementById('checkout-btn');
            checkoutBtn.disabled = true;
            checkoutBtn.textContent = 'Memproses...';
            
            const deviceInfo = {
                userAgent: navigator.userAgent,
                platform: navigator.platform
            };
            
            fetch('/attendance/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    latitude: currentLocation.latitude,
                    longitude: currentLocation.longitude,
                    device_info: JSON.stringify(deviceInfo)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Success
                    attendanceStatus.textContent = 'Sudah Presensi Pulang';
                    attendanceStatus.classList.remove('bg-orange-100', 'text-orange-600', 'bg-green-100', 'text-green-600');
                    attendanceStatus.classList.add('bg-red-100', 'text-red-600');
                    
                    attendanceResult.innerHTML = `
                        <div class="bg-green-50 border border-green-200 rounded p-3">
                            <p class="text-green-700 font-medium">Presensi pulang berhasil!</p>
                            <p class="text-sm text-green-600">Jam masuk: ${data.data.check_in}</p>
                            <p class="text-sm text-green-600">Jam pulang: ${data.data.check_out}</p>
                            <p class="text-xs text-green-600 mt-1">${data.data.schedule}</p>
                        </div>
                    `;
                } else {
                    showError(data.message);
                    checkoutBtn.disabled = false;
                    checkoutBtn.textContent = 'Presensi Pulang';
                }
            })
            .catch(err => {
                console.error('Error submitting checkout:', err);
                showError(err.message || 'Terjadi kesalahan saat checkout');
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Presensi Pulang';
            });
        }
        
        // Check if we've already done attendance today
        checkTodayAttendance();
        
        // Get location
        getLocation();
        
        // Event listeners
        tokenInput.addEventListener('input', function() {
            submitBtn.disabled = !this.value || this.value.length < 6 || !currentLocation;
        });
        
        submitBtn.addEventListener('click', submitAttendance);
        
        function getLocation() {
            locationStatus.innerHTML = `
                <div class="flex items-center">
                    <span class="text-yellow-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mendeteksi lokasi...
                    </span>
                </div>
            `;
            
            console.log("Getting location...");
            navigator.geolocation.getCurrentPosition(
                position => {
                    console.log("Location obtained:", position.coords);
                    currentLocation = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    
                    // Calculate distance from hospital using config values
                    const distance = calculateDistance(
                        position.coords.latitude,
                        position.coords.longitude,
                        hospitalConfig.latitude,
                        hospitalConfig.longitude
                    );
                    
                    // Show distance information
                    hospitalDistance.classList.remove('hidden');
                    distanceValue.textContent = `${Math.round(distance)} meter`;
                    
                    // Update location status
                    locationStatus.innerHTML = `
                        <div class="flex items-center">
                            <span class="text-green-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lokasi terdeteksi
                            </span>
                        </div>
                    `;
                    
                    // Determine if user is in the hospital area using config radius
                    const inHospitalArea = distance <= hospitalConfig.radius;
                    
                    if (inHospitalArea) {
                        distanceStatus.innerHTML = `
                            <span class="text-green-600">
                                <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Anda berada dalam radius yang diizinkan (${hospitalConfig.radius}m)
                            </span>
                        `;
                    } else {
                        distanceStatus.innerHTML = `
                            <span class="text-red-600">
                                <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Anda berada di luar radius yang diizinkan (maks. ${hospitalConfig.radius}m)
                            </span>
                        `;
                    }
                    
                    // Enable the submit button if there's a valid token
                    if (tokenInput && tokenInput.value && tokenInput.value.length >= 6) {
                        submitBtn.disabled = false;
                    }
                },
                error => {
                    console.error("Geolocation error:", error.code, error.message);
                    locationStatus.innerHTML = `
                        <div class="flex items-center">
                            <span class="text-red-600 mr-2">Gagal mendeteksi lokasi</span>
                            <button class="text-blue-600 underline" onclick="getLocation()">Coba lagi</button>
                        </div>
                    `;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        
        function submitAttendance() {
            if (!tokenInput.value || !currentLocation) {
                if (!tokenInput.value) {
                    showError('Silakan masukkan kode presensi');
                } else if (!currentLocation) {
                    showError('Menunggu data lokasi...');
                    getLocation();
                }
                return;
            }
            
            const token = tokenInput.value.trim().toUpperCase();
            
            if (token.length < 6) {
                showError('Kode presensi harus 6 karakter');
                return;
            }
            
            // Disable input and button
            tokenInput.disabled = true;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            
            // Get device info
            const deviceInfo = {
                userAgent: navigator.userAgent,
                platform: navigator.platform
            };
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                showError('Error CSRF token tidak ditemukan');
                
                tokenInput.disabled = false;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
                return;
            }
            
            fetch('/attendance/submit-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    token: token,
                    latitude: currentLocation.latitude,
                    longitude: currentLocation.longitude,
                    device_info: JSON.stringify(deviceInfo)
                })
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 500) {
                        throw new Error('Internal Server Error. Silakan coba lagi nanti.');
                    }
                    return response.text().then(text => {
                        throw new Error(`Error ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status) {
                    // Success
                    attendanceStatus.textContent = 'Sudah Presensi';
                    attendanceStatus.classList.remove('bg-orange-100', 'text-orange-600', 'bg-red-100', 'text-red-600');
                    attendanceStatus.classList.add('bg-green-100', 'text-green-600');
                    
                    attendanceResult.classList.remove('hidden');
                    attendanceResult.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                            <p class="text-green-700 font-medium">Presensi berhasil!</p>
                            <p class="text-sm text-green-600">Jam masuk: ${data.data.check_in_time}</p>
                            <p class="text-xs text-green-600 mt-1">${data.data.schedule.stase} - ${data.data.schedule.class}</p>
                            <div class="mt-1 mb-2">
                                <p class="text-xs text-gray-500">Kode presensi: <span class="font-mono font-medium">${data.data.token}</span></p>
                            </div>
                            <button id="checkout-btn" class="w-full bg-[#637F26] hover:bg-[#4e6320] text-white font-medium py-2 px-4 rounded-md text-sm">
                                Presensi Pulang
                            </button>
                        </div>
                    `;
                    
                    tokenInput.disabled = true;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Submit';
                    
                    document.getElementById('checkout-btn').addEventListener('click', submitCheckout);
                } else {
                    showError(data.message);
                    
                    tokenInput.disabled = false;
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                    
                    if (data.data && data.data.distance) {
                        attendanceResult.classList.remove('hidden');
                        attendanceResult.innerHTML = `
                            <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                <p class="text-yellow-700 font-medium">Anda berada di luar area presensi</p>
                                <p class="text-sm text-yellow-600">Jarak Anda: ${data.data.distance} meter (maksimal ${hospitalConfig.radius} meter)</p>
                                <p class="text-xs text-yellow-600 mt-1">Silahkan mendekat ke lokasi presensi</p>
                            </div>
                        `;
                    }
                }
            })
            .catch(err => {
                console.error('Error submitting attendance:', err);
                showError(err.message || 'Terjadi kesalahan saat mengirim presensi');
                
                tokenInput.disabled = false;
                submitBtn.disabled = currentLocation ? false : true;
                submitBtn.textContent = 'Submit';
            });
        }
    });
</script>

<script>
    // The Haversine formula calculation
    function calculateDistance(lat1, lon1, lat2, lon2) {
        // Convert degrees to radians
        lat1 = deg2rad(lat1);
        lon1 = deg2rad(lon1);
        lat2 = deg2rad(lat2);
        lon2 = deg2rad(lon2);
        
        // Haversine formula
        const dlat = lat2 - lat1;
        const dlon = lon2 - lon1;
        const a = Math.sin(dlat/2) * Math.sin(dlat/2) + 
                  Math.cos(lat1) * Math.cos(lat2) * 
                  Math.sin(dlon/2) * Math.sin(dlon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        
        // Earth's radius in meters
        const radius = 6371000;
        
        // Distance in meters
        return radius * c;
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }
</script>
@endpush