<nav class="top-navbar d-flex justify-content-between align-items-center">
    <button id="sidebarToggle" class="btn btn-sm border-0 d-md-none">
        <i class="bi bi-list fs-5"></i>
    </button>
    
    <div class="d-flex align-items-center">
        <div class="status-badge active">
            Status Magang: <strong>Aktif</strong>
        </div>
    </div>
    
    <div class="d-flex align-items-center">
        <!-- Notifications -->
        <div class="dropdown me-3">
            <a href="#" class="position-relative text-secondary" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Pergantian Jadwal</h6>
                            <small class="text-muted">3 jam yang lalu</small>
                        </div>
                        <small class="text-muted">Kelas FK-01 pada departemen kesehatan tetap perkuliahan...</small>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Nilai Ujian Tersedia</h6>
                            <small class="text-muted">kemarin</small>
                        </div>
                        <small class="text-muted">Nilai ujian Poli Mata sudah tersedia</small>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="#">Tampilkan Semua</a></li>
            </ul>
        </div>
        
        <!-- User Profile -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->photo_profile_url ?? 'https://ui-avatars.com/api/?name=Student+User&background=0D8ABC&color=fff' }}" alt="User Avatar" class="avatar">
                <span class="ms-2 d-none d-md-inline">{{ Auth::user()->name ?? 'Student User' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('student-profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    // Toggle sidebar untuk tampilan mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('d-none');
    });
</script>
@endpush