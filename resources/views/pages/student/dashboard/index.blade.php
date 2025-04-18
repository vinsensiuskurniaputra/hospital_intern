@extends('layouts.student')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Quick Action Panel -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Quick Action</h5>
                    <hr class="mt-3 mb-4">
                    <h6 class="fw-semibold">Presensi</h6>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Status</span>
                            <span class="badge bg-warning text-dark">Belum Presensi</span>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Masukkan Kode Presensi" aria-label="Kode Presensi">
                            <button class="btn btn-success" type="button">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kehadiran Panel -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Kehadiran</h5>
                    <p class="text-muted">1000 Kehadiran</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container" style="position: relative; height:200px; width:200px">
                                <canvas id="kehadiranChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge rounded-pill bg-success me-2">&nbsp;</span>
                                    <span>Hadir</span>
                                    <span class="ms-auto">52.1%</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge rounded-pill bg-warning me-2">&nbsp;</span>
                                    <span>Izin</span>
                                    <span class="ms-auto">22.8%</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-pill bg-danger me-2">&nbsp;</span>
                                    <span>Alpha</span>
                                    <span class="ms-auto">13.9%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Jadwal Hari Ini</h5>
                    <div class="row mt-3">
                        @for ($i = 0; $i < 4; $i++)
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <h6 class="card-title fw-semibold mb-2">Kelas FK-01</h6>
                                    <div class="d-flex align-items-center text-muted mb-2">
                                        <i class="bi bi-clock me-2"></i>
                                        <span>11:00 - 14:00</span>
                                    </div>
                                    <div class="text-muted small">Departemen Kesehatan</div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Notifikasi Panel -->
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Notifikasi / Pengumuman Penting</h5>
                    @for ($i = 0; $i < 3; $i++)
                    <div class="border-bottom py-3">
                        <h6 class="fw-semibold mb-1">Pergantian Jadwal</h6>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>03 Jul 2023 - 13:01</span>
                        </div>
                        <p class="mb-0 small">Kelas FK-01 pada departemen kesehatan tetap perkuliahan jadwal minggu tanggal 15 januari</p>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Nilai Panel -->
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Riwayat Penilaian</h5>
                    
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-semibold">Ujian Poli Mata</h6>
                                <small class="text-muted">09 Juli 2024</small>
                            </div>
                            <div class="px-3 py-2 bg-success text-white rounded fw-bold" style="width: 60px; text-align: center;">
                                90
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-semibold">Ujian Poli THT</h6>
                                <small class="text-muted">11 Juli 2024</small>
                            </div>
                            <div class="px-3 py-2 bg-danger text-white rounded fw-bold" style="width: 60px; text-align: center;">
                                50
                            </div>
                        </div>
                    </div>
                    
                    <div class="py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-semibold">Ujian Poli Kulit</h6>
                                <small class="text-muted">25 Juli 2024</small>
                            </div>
                            <div class="px-3 py-2 bg-warning text-dark rounded fw-bold" style="width: 60px; text-align: center;">
                                75
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('kehadiranChart').getContext('2d');
    const kehadiranChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Alpha'],
            datasets: [{
                data: [52.1, 22.8, 13.9],
                backgroundColor: [
                    '#28a745', // Hijau untuk Hadir
                    '#ffc107', // Kuning untuk Izin
                    '#dc3545'  // Merah untuk Alpha
                ],
                borderWidth: 0
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
            cutout: '70%'
        }
    });
});
</script>
@endpush