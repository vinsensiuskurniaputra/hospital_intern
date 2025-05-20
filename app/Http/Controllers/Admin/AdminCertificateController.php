<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Student;
use Barryvdh\DomPDF\PDF;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::where('is_finished', true)->paginate(10);   
        return view('pages.admin.certificate.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        //
    }

    public function generateCertificate($id, PDF $pdfGenerator)
    {
        $student = Student::findOrFail($id);

        $nama = $student->user->name;

        // Format kode sertifikat
        $tanggal = Carbon::now()->format('Ymd');
        $countToday = Certificate::whereDate('created_at', Carbon::today())->count() + 1;
        $urut = str_pad($countToday, 4, '0', STR_PAD_LEFT);
        $kode = "CERT-{$tanggal}-{$urut}";

        // Generate PDF dari view
        $data = [
            'nama' => $nama,
            'kode' => $kode,
        ];
        $pdf = $pdfGenerator->loadView('components.template', $data);

        // Simpan PDF ke storage/app/public/sertifikat/
        $filename = "{$kode}.pdf";
        $path = "public/sertifikat/{$filename}";
        Storage::put($path, $pdf->output());

        // Buat URL (agar bisa diakses via browser/public)
        $certificateUrl = Storage::url("sertifikat/{$filename}"); // hasilnya /storage/sertifikat/CERT-xxx.pdf

        // Simpan ke database
        Certificate::create([
            'student_id' => $student->id,
            'kode' => $kode,
            'certificate_url' => $certificateUrl,
        ]);

        // Nama file untuk diunduh
        $safeNama = Str::slug($nama, '_');
        return $pdf->download("sertifikat-{$safeNama}-{$kode}.pdf");
    }
    
    public function view($id)
    {
        $certificate = Certificate::findOrFail($id);
        @dd($certificate);

        // Ambil path dari field `certificate_url` (misal: /storage/sertifikat/CERT-xxxx.pdf)
        $relativePath = str_replace('/storage/', '', $certificate->certificate_url); // hasil: sertifikat/xxx.pdf

        // Buat full path storage
        $path = storage_path("app/public/{$relativePath}");

        if (!file_exists($path)) {
            abort(404, 'Sertifikat tidak ditemukan');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
