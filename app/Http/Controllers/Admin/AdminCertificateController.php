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

    public function generateCertificate($id)
    {
        $student = Student::findOrFail($id);

        // Generate certificate code
        $tanggal = Carbon::now()->format('Ymd');
        $countToday = Certificate::whereDate('created_at', Carbon::today())->count() + 1;
        $urut = str_pad($countToday, 4, '0', STR_PAD_LEFT);
        $kode = "CERT-{$tanggal}-{$urut}";


        // Save certificate details to the database
        Certificate::create([
            'student_id' => $student->id,
            'kode' => $kode,
            'certificate_url' => '-',
        ]);

        // Return the PDF file for download
        return redirect()->route('admin.certificates.index')->with('success', 'Departement created successfully');
    }

    public function downloadCertificate($id, PDF $pdfGenerator)
    {
        $student = Student::findOrFail($id);

        $nama = $student->user->name;
        $kode = $student->certificate->kode;

        // Prepare data for the PDF
        $data = ['nama' => $nama, 'kode' => $kode];
        $pdf = $pdfGenerator->loadView('components.admin.certificate.template', $data);
        $pdf->set_paper('A4', 'portrait');
        $filename = "{$kode}.pdf";

        // Just return the PDF file for download
        return $pdf->download($filename);
    }


}
