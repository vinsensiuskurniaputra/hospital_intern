@extends('layouts.auth')

@section('content')
    @if(isset($error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ $error }}</p>
    </div>
    @endif

    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Quick Action -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-4">Quick Action</h2>
                    
                    <div class="mb-6">
                        <h3 class="text-base font-medium mb-2">Presensi</h3>
                        <div id="presence-token-status">
                            <div class="flex items-center text-sm mb-3">
                                <span class="mr-2">Status Kode:</span>
                                <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded-md" id="token-status-badge">Memuat...</span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div id="token-display" class="hidden">
                                <div class="bg-gray-100 p-4 rounded-lg text-center mb-3">
                                    <p class="text-sm text-gray-600 mb-1">Kode Aktif:</p>
                                    <div class="text-2xl font-bold tracking-wide" id="active-token">-</div>
                                    <p class="text-xs text-gray-500 mt-1" id="token-expiry">-</p>
                                    <p class="text-sm font-medium mt-2" id="token-schedule">-</p>
                                </div>
                            </div>
                            
                            <div id="generate-token-form">
                                <div class="mb-3">
                                    <select id="schedule-select" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 mb-2">
                                        <option value="">-- Pilih Jadwal --</option>
                                    </select>
                                </div>
                                
                                <button id="generate-token-btn" class="w-full bg-[#637F26] hover:bg-[#566d1e] text-white py-3 rounded-md transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                                    Generate Kode
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Mahasiswa yang dibimbing -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-1">Mahasiswa yang dibimbing</h2>
                    <div class="text-2xl font-bold mb-1">{{ $studentCount ?? 0 }}</div>
                    <p class="text-sm text-gray-500 mb-4">Mahasiswa dalam bimbingan anda</p>
                    <div class="flex justify-end">
                        <a href="{{ route('responsible.attendance') }}" class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </a>
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $displayedScheduleIds = [];
                        @endphp
                        
                        @forelse($todaySchedules as $schedule)
                            @if(!in_array($schedule->id, $displayedScheduleIds))
                                @php
                                    $displayedScheduleIds[] = $schedule->id;
                                @endphp
                                <div class="bg-gray-100 rounded-lg p-4 shadow">
                                    <h3 class="font-medium">{{ $schedule->internshipClass->name ?? 'Kelas' }}</h3>
                                    <div class="text-xs text-gray-500 mt-1">{{ $schedule->stase->name ?? 'Departemen' }}</div>
                                </div>
                            @endif
                        @empty
                        <div class="col-span-2 text-center p-6 text-gray-500">
                            <p>Tidak ada jadwal untuk hari ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Kehadiran Mahasiswa Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3">Kehadiran Mahasiswa</h2>
                    
                    <div class="mb-3">
                        <div class="text-sm text-gray-600">Total Kehadiran</div>
                        <div class="text-2xl font-bold">{{ array_sum($chartData['data'] ?? [0]) }} Kehadiran</div>
                        <div class="flex items-center mt-1">
                            @php
                                $labels = $chartData['labels'] ?? ['Jan'];
                                $lastMonthIndex = count($labels) - 1;
                                $lastMonth = $lastMonthIndex >= 0 ? $labels[$lastMonthIndex] : 'Jan';
                            @endphp
                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-md mr-2">{{ $lastMonth }}</span>
                            @php
                                $dataArray = $chartData['data'] ?? [0, 0];
                                $currentMonthIndex = count($dataArray) - 1;
                                $prevMonthIndex = $currentMonthIndex - 1;
                                
                                $currentMonth = $currentMonthIndex >= 0 ? $dataArray[$currentMonthIndex] : 0;
                                $previousMonth = $prevMonthIndex >= 0 ? $dataArray[$prevMonthIndex] : 0;
                                
                                $change = $previousMonth > 0 ? (($currentMonth - $previousMonth) / $previousMonth) * 100 : 0;
                            @endphp
                            <span class="flex items-center {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }} text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ $change >= 0 ? '13 7l5 5m0 0l-5 5m5-5H6' : '11 17l-5-5m0 0l5-5m-5 5h12' }}"></path>
                                </svg>
                                {{ number_format(abs($change), 1) }}%
                            </span>
                        </div>
                    </div>
                    
                    <!-- Chart Canvas dengan padding dan border atas yang tipis -->
                    <div class="h-40 mt-6 mb-4 pt-4 border-t border-gray-100">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <!-- Mahasiswa yang harus dinilai -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Mahasiswa yang harus dinilai</h2>
                        <a href="{{ route('responsible.grades') }}" class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($studentsToGrade as $student)
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name ?? 'Student') }}" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">{{ $student->user->name ?? 'Nama Mahasiswa' }}</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">{{ $student->nim ?? 'NIM' }}</div>
                            <div class="text-sm text-gray-500">{{ $student->internshipClass->name ?? 'Kelas' }}</div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Tidak ada mahasiswa yang perlu dinilai</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi / Pengumuman Penting (Full width) -->
        <div class="bg-white p-6 rounded-lg shadow-sm mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Notifikasi / Pengumuman Penting</h2>
                <button class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Semua
                </button>
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                <div class="border-b border-gray-100 pb-4 {{ $loop->last ? '' : 'border-b' }}">
                    <h3 class="font-medium mb-1">{{ $notification->title ?? 'Notifikasi' }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $notification->message ?? 'Tidak ada pesan' }}</p>
                    <div class="text-xs text-gray-400 text-right">
                        @if($notification->created_at)
                            {{ $notification->created_at->format('d M Y - H:i') }}
                        @else
                            {{ now()->format('d M Y - H:i') }}
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="text-center text-gray-500 text-xs mt-8">@2025 IK Polines</div>
    </div>

    <!-- Generate Token Modal -->
    <div id="generate-token-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Generate Kode Presensi</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="modal-schedule-select" class="block text-sm font-medium text-gray-700 mb-1">Jadwal</label>
                    <select id="modal-schedule-select" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Pilih Jadwal --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih jadwal untuk generate kode presensi</p>
                </div>
                
                <div class="bg-blue-50 p-3 rounded text-sm">
                    <p class="flex items-center text-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi
                    </p>
                    <p class="mt-1 text-blue-600">Kode presensi akan aktif hingga akhir hari ini. Mahasiswa dapat menggunakan kode ini untuk presensi pada jadwal yang dipilih.</p>
                </div>
                
                <div class="flex justify-end pt-2">
                    <button id="cancel-generate" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                        Batal
                    </button>
                    <button id="confirm-generate" class="bg-[#637F26] hover:bg-[#566d1e] text-white font-medium py-2 px-4 rounded-md">
                        Generate Kode
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart');
            
            if (ctx) {
                try {
                    // Data dari controller
                    const labels = {!! json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']) !!};
                    const chartData = {!! json_encode($chartData['data'] ?? [0, 0, 0, 0, 0, 0, 0]) !!};
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Kehadiran Mahasiswa',
                                data: chartData,
                                borderColor: '#637F26',
                                backgroundColor: 'rgba(240, 240, 245, 0.7)',
                                pointBackgroundColor: function(context) {
                                    return context.dataIndex === labels.length - 1 ? '#637F26' : 'transparent';
                                },
                                pointBorderColor: function(context) {
                                    return context.dataIndex === labels.length - 1 ? '#fff' : 'transparent';
                                },
                                pointBorderWidth: 2,
                                pointRadius: function(context) {
                                    return context.dataIndex === labels.length - 1 ? 6 : 0;
                                },
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        // Menampilkan jumlah kehadiran alih-alih persentase
                                        label: function(context) {
                                            return `${context.parsed.y} kehadiran`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
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
                } catch (e) {
                    console.error('Error creating chart:', e);
                    ctx.parentNode.innerHTML = '<div class="p-4 text-center text-gray-500">Grafik tidak dapat ditampilkan: ' + e.message + '</div>';
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Token generation functionality
            const tokenStatus = document.getElementById('token-status-badge');
            const tokenDisplay = document.getElementById('token-display');
            const activeToken = document.getElementById('active-token');
            const tokenExpiry = document.getElementById('token-expiry');
            const tokenSchedule = document.getElementById('token-schedule');
            const scheduleSelect = document.getElementById('schedule-select');
            const generateBtn = document.getElementById('generate-token-btn');
            
            // Modal elements
            const modal = document.getElementById('generate-token-modal');
            const modalScheduleSelect = document.getElementById('modal-schedule-select');
            const closeModal = document.getElementById('close-modal');
            const cancelGenerate = document.getElementById('cancel-generate');
            const confirmGenerate = document.getElementById('confirm-generate');
            
            // Check active tokens on load
            checkActiveTokens();
            
            // Event listeners
            generateBtn.addEventListener('click', showGenerateModal);
            closeModal.addEventListener('click', hideGenerateModal);
            cancelGenerate.addEventListener('click', hideGenerateModal);
            confirmGenerate.addEventListener('click', generateToken);
            
            // Check for active tokens every 60 seconds
            setInterval(checkActiveTokens, 60000);
            
            function showGenerateModal() {
                modal.classList.remove('hidden');
                // Clone schedule options to modal
                modalScheduleSelect.innerHTML = scheduleSelect.innerHTML;
                modalScheduleSelect.value = scheduleSelect.value;
            }
            
            function hideGenerateModal() {
                modal.classList.add('hidden');
            }
            
            function checkActiveTokens() {
                // Show loading state
                tokenStatus.textContent = 'Memuat...';
                
                fetch('/presence/active-tokens')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Server error: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status) {
                            // Populate schedule select with options that include has_active_token info
                            populateScheduleOptions(data.data.schedule_options);
                            
                            // Clear previous tokens display
                            if (tokenDisplay && tokenDisplay.querySelector('.mt-3')) {
                                tokenDisplay.querySelector('.mt-3').remove();
                            }
                            
                            // Check active tokens
                            const activeTokens = data.data.active_tokens;
                            if (activeTokens && activeTokens.length > 0) {
                                // Show active token
                                const token = activeTokens[0];
                                tokenStatus.textContent = 'Aktif';
                                tokenStatus.classList.remove('bg-amber-100', 'text-amber-800');
                                tokenStatus.classList.add('bg-green-100', 'text-green-800');
                                
                                tokenDisplay.classList.remove('hidden');
                                activeToken.textContent = token.token;
                                
                                if (token.expiration_time) {
                                    const expiry = new Date(token.expiration_time);
                                    tokenExpiry.textContent = `Berlaku hingga ${formatDateTime(expiry)}`;
                                } else {
                                    tokenExpiry.textContent = 'Berlaku hingga akhir hari';
                                }
                                
                                tokenSchedule.textContent = `${token.stase_name} - ${token.schedule_name}`;
                                
                                // Show all available tokens if there are multiple
                                if (activeTokens.length > 1) {
                                    let html = '<div class="mt-3"><p class="text-sm font-medium mb-2">Token Aktif Lainnya:</p><div class="space-y-4">';
                                    
                                    for (let i = 1; i < activeTokens.length; i++) {
                                        const t = activeTokens[i];
                                        // Gunakan format yang sama dengan token aktif utama
                                        html += `
            <div class="bg-gray-100 p-4 rounded-lg text-center">
                <p class="text-sm text-gray-600 mb-1">Kode Aktif:</p>
                <div class="text-2xl font-bold tracking-wide">${t.token}</div>
                <p class="text-xs text-gray-500 mt-1">${t.expiration_time ? 'Berlaku hingga ' + formatDateTime(new Date(t.expiration_time)) : 'Berlaku hingga akhir hari'}</p>
                <p class="text-sm font-medium mt-2">${t.stase_name} - ${t.schedule_name}</p>
            </div>
        `;
                                    }
                                    
                                    html += '</div></div>';
                                    document.getElementById('token-display').insertAdjacentHTML('beforeend', html);
                                }
                            } else {
                                // No active token
                                tokenStatus.textContent = 'Belum Aktif';
                                tokenStatus.classList.remove('bg-green-100', 'text-green-800');
                                tokenStatus.classList.add('bg-amber-100', 'text-amber-800');
                                
                                tokenDisplay.classList.add('hidden');
                            }
                        } else {
                            tokenStatus.textContent = 'Error';
                            console.error('Failed to fetch active tokens:', data.message);
                        }
                    })
                    .catch(err => {
                        tokenStatus.textContent = 'Error';
                        console.error('Error checking active tokens:', err);
                        showNotification('Gagal memuat data token: ' + err.message, 'error');
                    });
            }
            
            function populateScheduleOptions(options) {
                // Clear existing options except the placeholder
                while (scheduleSelect.options.length > 1) {
                    scheduleSelect.remove(1);
                }
                
                // Add new options
                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt.id;
                    option.textContent = opt.name;
                    
                    // Disable options that already have active tokens
                    if (opt.has_active_token) {
                        option.disabled = true;
                        option.textContent += ' (Token Aktif)';
                    }
                    
                    scheduleSelect.appendChild(option);
                });
            }
            
            function generateToken() {
                const scheduleId = modalScheduleSelect.value;
                
                if (!scheduleId) {
                    alert('Pilih jadwal terlebih dahulu');
                    return;
                }
                
                confirmGenerate.disabled = true;
                confirmGenerate.textContent = 'Generating...';
                
                fetch('/presence/generate-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Server error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status) {
                        // Success, close modal and refresh tokens
                        hideGenerateModal();
                        checkActiveTokens();
                        
                        // Show success notification
                        showNotification('Kode berhasil dibuat dan berlaku hingga akhir hari ini', 'success');
                    } else {
                        alert('Gagal membuat kode: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Error generating token:', err);
                    alert('Terjadi kesalahan saat membuat kode: ' + err.message);
                })
                .finally(() => {
                    confirmGenerate.disabled = false;
                    confirmGenerate.textContent = 'Generate Kode';
                });
            }
            
            function formatDateTime(date) {
                const options = { 
                    day: '2-digit', 
                    month: 'short', 
                    hour: '2-digit', 
                    minute: '2-digit'
                };
                return date.toLocaleDateString('id-ID', options);
            }
            
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.classList.add(
                    'fixed', 'bottom-4', 'right-4', 'p-4', 'rounded-md', 'shadow-md', 'z-50',
                    'transform', 'transition-all', 'duration-300', 'translate-y-full',
                    'flex', 'items-center'
                );
                
                if (type === 'success') {
                    notification.classList.add('bg-green-100', 'text-green-800', 'border-l-4', 'border-green-500');
                } else if (type === 'error') {
                    notification.classList.add('bg-red-100', 'text-red-800', 'border-l-4', 'border-red-500');
                } else {
                    notification.classList.add('bg-blue-100', 'text-blue-800', 'border-l-4', 'border-blue-500');
                }
                
                notification.innerHTML = `
                    <div class="flex-1">
                        ${message}
                    </div>
                    <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                document.body.appendChild(notification);
                
                // Animation
                setTimeout(() => {
                    notification.classList.remove('translate-y-full');
                    notification.classList.add('translate-y-0');
                }, 100);
                
                setTimeout(() => {
                    notification.classList.remove('translate-y-0');
                    notification.classList.add('translate-y-full');
                }, 5000);
                
                setTimeout(() => {
                    notification.remove();
                }, 5500);
            }
        });
    </script>
    @endpush
@endsection