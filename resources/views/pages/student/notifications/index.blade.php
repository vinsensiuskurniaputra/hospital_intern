@extends('layouts.auth')

@section('content')
<div class="p-4 sm:p-6 md:p-8">
    <div class="bg-white rounded-lg shadow">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h2>
            <div class="relative">
                <button class="px-3 py-1.5 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors flex items-center gap-2">
                    Filter
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="space-y-3 p-4 sm:p-6">
            @foreach([
                [
                    'title' => 'Pengambilan Sertifikat Magang',
                    'date' => '05 Agustus 2024 - 23:59',
                    'content' => 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
                    'tag' => 'Umum',
                    'tag_color' => 'emerald'
                ],
                [
                    'title' => 'Pergantian Jadwal',
                    'date' => '09 Juli 2024 - 00:03',
                    'content' => 'Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.',
                    'tag' => 'Jadwal',
                    'tag_color' => 'amber'
                ],
                [
                    'title' => 'Jadwal Ujian Evaluasi Sebelum Rotasi Baru',
                    'date' => '28 Juni 2024 - 10:00',
                    'content' => 'Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Juni 2024 pukul 10:00 WIB.',
                    'tag' => 'Evaluasi',
                    'tag_color' => 'emerald'
                ],
                [
                    'title' => 'Kebijakan Baru Kedisiplinan Magang',
                    'date' => '29 Maret 2024 - 15:00',
                    'content' => 'Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.',
                    'tag' => 'Kebijakan',
                    'tag_color' => 'red'
                ],
                [
                    'title' => 'Pengumpulan Berkas Administrasi Magang',
                    'date' => '28 Februari 2024 - 12:00',
                    'content' => 'Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.',
                    'tag' => 'Administrasi',
                    'tag_color' => 'emerald'
                ]
            ] as $notification)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex justify-between items-start">
                        <h3 class="font-medium text-gray-900">{{ $notification['title'] }}</h3>
                        <span class="text-sm text-gray-500">{{ $notification['date'] }}</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">{{ $notification['content'] }}</p>
                    <div class="mt-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $notification['tag_color'] }}-100 text-{{ $notification['tag_color'] }}-800">
                            {{ $notification['tag'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
