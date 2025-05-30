@extends('layouts.auth')

@section('title', 'Presensi Mahasiswa')

@section('content')
<div class="w-full">
    <h4 class="mb-4 px-4 pt-4 font-semibold text-lg">Presensi Mahasiswa</h4>
    
    <div class="px-4 pb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <!-- Stase Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stase</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="stase-filter">
                    @if($stases->count() > 0)
                        @foreach($stases as $stase)
                            <option value="{{ $stase->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $stase->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">Tidak ada stase</option>
                    @endif
                </select>
            </div>
            
            <!-- Class Year Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Angkatan</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="class-year-filter">
                    <option value="">Pilih stase terlebih dahulu</option>
                </select>
            </div>
            
            <!-- Class Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="class-filter">
                    <option value="">Pilih tahun angkatan terlebih dahulu</option>
                </select>
            </div>
            
            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" value="{{ $today }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="date-filter">
            </div>
            
            <!-- Search Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <div class="relative">
                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search" id="search-filter">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10"></th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRODI</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK IN</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHECK OUT</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BUKTI</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="student-list">
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Silakan lengkapi filter untuk melihat data mahasiswa</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Presensi -->
<div id="editAttendanceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="attendance-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="student-id">
                <input type="hidden" id="attendance-date">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Presensi</h3>
                            <div class="mb-4">
                                <h4 class="text-md font-medium text-gray-700 mb-1">Nama Mahasiswa</h4>
                                <p id="student-name" class="text-gray-900"></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Presensi</label>
                                <select id="attendance-status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="present">Hadir</option>
                                    <option value="sick">Sakit</option>
                                    <option value="excused">Izin</option>
                                    <option value="absent">Absen</option>
                                </select>
                            </div>
                            <div id="proof-container" class="mb-4 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti</label>
                                <input type="file" id="proof-file" name="proof_file" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">No file selected.</p>
                                
                                <div class="mt-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                                    <textarea id="description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="save-attendance" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" id="cancel-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editAttendanceModal');
        const staseFilter = document.getElementById('stase-filter');
        const classYearFilter = document.getElementById('class-year-filter');
        const classFilter = document.getElementById('class-filter');
        const dateFilter = document.getElementById('date-filter');
        const searchFilter = document.getElementById('search-filter');
        
        // Function to load class years based on stase selection
        function loadClassYears(staseId) {
            // Clear dependent filters
            classYearFilter.innerHTML = '<option value="">Loading...</option>';
            classFilter.innerHTML = '<option value="">Pilih tahun angkatan terlebih dahulu</option>';
            clearStudentList('Silakan lengkapi filter untuk melihat data mahasiswa');
            
            if (!staseId) {
                classYearFilter.innerHTML = '<option value="">Pilih stase terlebih dahulu</option>';
                return;
            }
            
            fetch(`/responsible/attendance/class-years?stase_id=${staseId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    classYearFilter.innerHTML = '';
                    
                    if (data.class_years.length === 0) {
                        classYearFilter.innerHTML = '<option value="">Tidak ada tahun angkatan</option>';
                        clearStudentList('Tidak ada tahun angkatan untuk stase yang dipilih');
                        return;
                    }
                    
                    // Add placeholder option
                    const placeholderOption = document.createElement('option');
                    placeholderOption.value = '';
                    placeholderOption.textContent = 'Pilih tahun angkatan';
                    classYearFilter.appendChild(placeholderOption);
                    
                    // Add class year options
                    data.class_years.forEach(classYear => {
                        const option = document.createElement('option');
                        option.value = classYear.id;
                        option.textContent = classYear.class_year;
                        classYearFilter.appendChild(option);
                    });
                    
                    // Auto-select current year if available
                    const currentYear = new Date().getFullYear();
                    const currentYearOption = data.class_years.find(cy => cy.class_year == currentYear);
                    if (currentYearOption) {
                        classYearFilter.value = currentYearOption.id;
                        // Trigger class loading
                        loadClasses(staseId, currentYearOption.id);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading class years:', error);
                classYearFilter.innerHTML = '<option value="">Error loading class years</option>';
            });
        }
        
        // Function to load classes based on stase and class year selection
        function loadClasses(staseId, classYearId) {
            // Clear dependent filters
            classFilter.innerHTML = '<option value="">Loading...</option>';
            clearStudentList('Silakan lengkapi filter untuk melihat data mahasiswa');
            
            if (!staseId || !classYearId) {
                classFilter.innerHTML = '<option value="">Pilih stase dan tahun angkatan terlebih dahulu</option>';
                return;
            }
            
            fetch(`/responsible/attendance/classes?stase_id=${staseId}&class_year_id=${classYearId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    classFilter.innerHTML = '';
                    
                    if (data.classes.length === 0) {
                        classFilter.innerHTML = '<option value="">Tidak ada kelas</option>';
                        clearStudentList('Tidak ada kelas untuk stase dan tahun angkatan yang dipilih');
                        return;
                    }
                    
                    // Add placeholder option
                    const placeholderOption = document.createElement('option');
                    placeholderOption.value = '';
                    placeholderOption.textContent = 'Pilih kelas';
                    classFilter.appendChild(placeholderOption);
                    
                    // Add class options
                    data.classes.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.name;
                        classFilter.appendChild(option);
                    });
                    
                    // Auto-select first class if only one available
                    if (data.classes.length === 1) {
                        classFilter.value = data.classes[0].id;
                        // Trigger student loading
                        loadStudents();
                    }
                }
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classFilter.innerHTML = '<option value="">Error loading classes</option>';
            });
        }
        
        // Function to load students based on selected filters
        function loadStudents() {
            const staseId = staseFilter.value;
            const classYearId = classYearFilter.value;
            const classId = classFilter.value;
            const date = dateFilter.value;
            const search = searchFilter.value;
            
            // Don't load if required filters are not set
            if (!staseId || !classYearId || !classId || !date) {
                clearStudentList('Silakan lengkapi filter untuk melihat data mahasiswa');
                return;
            }
            
            // Format date for display
            const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            // Show loading indicator
            clearStudentList('Loading...');
            
            fetch(`/responsible/attendance/students?stase_id=${staseId}&class_year_id=${classYearId}&class_id=${classId}&date=${date}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.hasPresenceSession === false) {
                        document.getElementById('student-list').innerHTML = 
                            `<tr><td colspan="9" class="text-center py-8 text-gray-500">
                                <p class="text-base">Sesi Presensi belum dibuat untuk tanggal yang dipilih</p>
                                <p class="font-semibold text-lg mt-1">${formattedDate}</p>
                                <p class="text-sm mt-3">Silahkan pilih tanggal lain atau Generate token presensi untuk tanggal ini terlebih dahulu.</p>
                            </td></tr>`;
                    } else {
                        renderStudents(data.students, search);
                    }
                } else {
                    clearStudentList(`Error: ${data.message || 'Failed to load data'}`);
                }
            })
            .catch(error => {
                console.error('Error loading students:', error);
                clearStudentList('Failed to load student data');
            });
        }
        
        // Helper function to clear student list with a message
        function clearStudentList(message) {
            document.getElementById('student-list').innerHTML = 
                `<tr><td colspan="9" class="text-center py-4 text-gray-500">${message}</td></tr>`;
        }
        
        // Function to render students in the table
        function renderStudents(students, search = '') {
            const tbody = document.getElementById('student-list');
            tbody.innerHTML = '';
            
            // Filter students if search is provided
            if (search !== '') {
                const searchLower = search.toLowerCase();
                students = students.filter(student => {
                    return (
                        student.nim.toLowerCase().includes(searchLower) ||
                        student.user.name.toLowerCase().includes(searchLower) ||
                        student.study_program.name.toLowerCase().includes(searchLower)
                    );
                });
            }
            
            // If no students found
            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4">No students found</td></tr>';
                return;
            }
            
            // Render each student
            students.forEach(student => {
                const presence = student.presences.length > 0 ? student.presences[0] : null;
                const status = presence ? presence.status : '-';
                
                // Function to get status badge with colors
                function getStatusBadge(status) {
                    let statusText = '-';
                    let badgeClass = 'bg-gray-100 text-gray-800'; // Default for no status
                    
                    if (status === 'present') {
                        statusText = 'Hadir';
                        badgeClass = 'bg-green-100 text-green-800';
                    } else if (status === 'sick') {
                        statusText = 'Sakit';
                        badgeClass = 'bg-yellow-100 text-yellow-800';
                    } else if (status === 'excused') {
                        statusText = 'Izin';
                        badgeClass = 'bg-yellow-100 text-yellow-800';
                    } else if (status === 'absent') {
                        statusText = 'Absen';
                        badgeClass = 'bg-red-100 text-red-800';
                    }
                    
                    if (status === '-') {
                        return `<span class="text-sm text-gray-500">${statusText}</span>`;
                    }
                    
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">${statusText}</span>`;
                }
                
                // Format check-in time
                let checkInTime = '-';
                if (presence && presence.check_in) {
                    checkInTime = formatTime(presence.check_in);
                }
                
                // Format check-out time
                let checkOutTime = '-';
                if (presence && presence.check_out) {
                    checkOutTime = formatTime(presence.check_out);
                }
                
                let proofLink = '';
                if (presence && (status === 'sick' || status === 'excused')) {
                    const attendanceExcuse = student.attendance_excuses && student.attendance_excuses.find(excuse => 
                        excuse.presence_sessions_id === presence.presence_session_id
                    );
                    
                    if (attendanceExcuse && attendanceExcuse.letter_url) {
                        proofLink = `<a href="/storage/${attendanceExcuse.letter_url}" target="_blank" class="text-blue-600 hover:text-blue-900 text-sm underline">Lihat</a>`;
                    } else {
                        proofLink = '<span class="text-sm text-gray-500">-</span>';
                    }
                } else {
                    proofLink = '<span class="text-sm text-gray-500">-</span>';
                }
                
                const profilePhoto = student.user.photo_profile_url 
                    ? `/storage/${student.user.photo_profile_url}` 
                    : '/img/avatar/default.jpg';
                
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="pl-4 pr-2 py-4 whitespace-nowrap">
                            <div class="flex-shrink-0">
                                <input type="checkbox" class="hidden">
                                <img class="h-8 w-8 rounded-full" src="${profilePhoto}" alt="Profile">
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${student.nim}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${student.user.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${student.study_program.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${getStatusBadge(status)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">${checkInTime}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">${checkOutTime}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${proofLink}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md mr-1 edit-btn transition-colors" 
                                data-id="${student.id}" 
                                data-name="${student.user.name}" 
                                data-status="${status}"
                                title="Edit Presensi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
            
            // Reattach event listeners for edit buttons
            document.querySelectorAll('.edit-btn').forEach(attachEditButtonHandler);
        }
        
        // Function to attach edit button event handlers
        function attachEditButtonHandler(button) {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-id');
                const studentName = this.getAttribute('data-name');
                const status = this.getAttribute('data-status');
                
                document.getElementById('student-id').value = studentId;
                document.getElementById('student-name').textContent = studentName;
                document.getElementById('attendance-date').value = dateFilter.value;
                
                const statusSelect = document.getElementById('attendance-status');
                statusSelect.value = status !== '-' ? status : 'present';
                
                toggleProofContainer(statusSelect.value);
                modal.classList.remove('hidden');
            });
        }
        
        // Event listeners for filters
        staseFilter.addEventListener('change', function() {
            loadClassYears(this.value);
        });
        
        classYearFilter.addEventListener('change', function() {
            loadClasses(staseFilter.value, this.value);
        });
        
        classFilter.addEventListener('change', loadStudents);
        dateFilter.addEventListener('change', loadStudents);
        
        searchFilter.addEventListener('input', function() {
            clearTimeout(this._timeout);
            this._timeout = setTimeout(() => {
                loadStudents();
            }, 300);
        });
        
        // Event listener for status change in modal
        document.getElementById('attendance-status').addEventListener('change', function() {
            toggleProofContainer(this.value);
        });
        
        // Function to toggle proof container visibility
        function toggleProofContainer(status) {
            const proofContainer = document.getElementById('proof-container');
            if (status === 'sick' || status === 'excused') {
                proofContainer.classList.remove('hidden');
            } else {
                proofContainer.classList.add('hidden');
            }
        }
        
        // Event listener for modal cancel button
        document.getElementById('cancel-btn').addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Event listener for form submission
        document.getElementById('attendance-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('student_id', document.getElementById('student-id').value);
            formData.append('date', document.getElementById('attendance-date').value);
            
            fetch('/responsible/attendance/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal and reload students
                    modal.classList.add('hidden');
                    loadStudents();
                    
                    // Show success notification
                    alert('Data presensi berhasil diperbarui');
                } else {
                    alert('Failed to update attendance: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating attendance:', error);
                alert('An error occurred while updating attendance');
            });
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // IMPORTANT: Load initial data on page load
        if (staseFilter.value) {
            loadClassYears(staseFilter.value);
        }
    });
    
    // Helper function to format time from timestamp
    function formatTime(timeString) {
        if (!timeString) return '-';
        
        try {
            const date = new Date(timeString);
            if (isNaN(date)) {
                const parts = timeString.split(':');
                return `${parts[0]}:${parts[1]}`;
            }
            return date.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
        } catch (e) {
            console.error('Error formatting time:', e);
            return timeString;
        }
    }
</script>
@endpush