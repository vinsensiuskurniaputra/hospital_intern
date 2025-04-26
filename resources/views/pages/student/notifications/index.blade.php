<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Magang RS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r p-6 flex flex-col">
            <h1 class="text-2xl font-bold text-green-700 mb-10">SIM Magang RS</h1>
            <nav class="space-y-6 text-gray-700 text-sm font-medium">
                <a href="#" class="flex items-center hover:text-green-600">
                    <span class="material-icons mr-3">dashboard</span> Dashboard
                </a>
                <a href="#" class="flex items-center hover:text-green-600">
                    <span class="material-icons mr-3">calendar_today</span> Jadwal
                </a>
                <a href="#" class="flex items-center hover:text-green-600">
                    <span class="material-icons mr-3">assignment_turned_in</span> Presensi & Sertifikasi
                </a>
                <a href="#" class="flex items-center hover:text-green-600">
                    <span class="material-icons mr-3">grade</span> Nilai
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Top Navbar -->
            <header class="bg-white h-16 flex items-center justify-between px-8 border-b">
                <div></div> <!-- buat spasi kosong di kiri -->
                <div class="flex items-center space-x-6">
                    <span class="material-icons text-gray-600 cursor-pointer">notifications</span>
                    <img src="https://via.placeholder.com/40" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                </div>
            </header>

            <!-- Content -->
            <main class="p-8 flex-1">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold">Notifikasi / Pengumuman Penting</h2>
                        <button class="bg-gray-200 px-4 py-2 rounded-lg text-sm">Filter</button>
                    </div>

                    <!-- Notification List -->
                    <div class="space-y-4">

                        <!-- Pengambilan Sertifikat -->
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">Pengambilan Sertifikat Magang</h3>
                                <span class="text-sm text-gray-600">05 Agustus 2024 - 23:59</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">
                                Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.
                            </p>
                            <span class="inline-block mt-3 px-3 py-1 bg-green-200 text-green-800 text-xs rounded-full">Umum</span>
                        </div>

                        <!-- Pergantian Jadwal -->
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">Pergantian Jadwal</h3>
                                <span class="text-sm text-gray-600">09 Juli 2024 - 00:03</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">
                                Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.
                            </p>
                            <span class="inline-block mt-3 px-3 py-1 bg-yellow-200 text-yellow-800 text-xs rounded-full">Jadwal</span>
                        </div>

                        <!-- Jadwal Ujian Evaluasi -->
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">Jadwal Ujian Evaluasi Sebelum Rotasi Baru</h3>
                                <span class="text-sm text-gray-600">28 Juni 2024 - 10:00</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">
                                Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Juni 2024 pukul 10:00 WIB.
                            </p>
                            <span class="inline-block mt-3 px-3 py-1 bg-green-200 text-green-800 text-xs rounded-full">Evaluasi</span>
                        </div>

                        <!-- Kebijakan Baru -->
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">Kebijakan Baru Kedisiplinan Magang</h3>
                                <span class="text-sm text-gray-600">29 Maret 2024 - 15:00</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">
                                Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.
                            </p>
                            <span class="inline-block mt-3 px-3 py-1 bg-red-200 text-red-800 text-xs rounded-full">Kebijakan</span>
                        </div>

                        <!-- Pengumpulan Berkas -->
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">Pengumpulan Berkas Administrasi Magang</h3>
                                <span class="text-sm text-gray-600">28 Februari 2024 - 12:00</span>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">
                                Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.
                            </p>
                            <span class="inline-block mt-3 px-3 py-1 bg-green-300 text-green-900 text-xs rounded-full">Administrasi</span>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <footer class="text-center text-gray-500 text-xs mt-10">
                    ©2025 IK Polines
                </footer>

            </main>
        </div>

    </div>

</body>
</html>
