<nav id="sidebarMenu" class="sidebar collapse d-md-block">
    <div class="sidebar-sticky">
        <div class="sidebar-logo">
            <a href="{{ route('student.dashboard') ?? '#' }}" class="text-decoration-none">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') ?? 'https://via.placeholder.com/40x40' }}" alt="Logo" height="36">
                    <span class="ms-3 fw-semibold text-dark">SIM Magang RS</span>
                </div>
            </a>
        </div>
        
        <div class="mb-3">
            <span class="text-uppercase text-muted small px-4">Menu</span>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('student.dashboard') ?? '#' }}" class="nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.schedule') ?? '#' }}" class="nav-link {{ request()->is('student/schedule*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    Jadwal
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.attendance') ?? '#' }}" class="nav-link {{ request()->is('student/attendance*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i>
                    Presensi & Sertifikasi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('student.grades') ?? '#' }}" class="nav-link {{ request()->is('student/grades*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i>
                    Nilai
                </a>
            </li>
        </ul>
    </div>
</nav>