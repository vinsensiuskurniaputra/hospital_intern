<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Campus;
use App\Models\Student;
use App\Models\ClassYear;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['certificate', 'user', 'studyProgram', 'internshipClass'])->paginate(10);
        $studentCount = Student::count();
        $studyPrograms = StudyProgram::all();
        $campuses = Campus::all();
        $classYears = ClassYear::all();
        return view('pages.admin.student.index', compact('students', 'studentCount', 'studyPrograms','campuses','classYears'));
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
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'internship_class_id' => 'nullable|exists:internship_classes,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'nim' => 'required|string|unique:students,nim',
            'telp' => 'required|string',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo_profile')) {
            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        }

        $user = User::addUser([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'password' => 'password',
            'photo_profile_url' => $photoPath,
        ]);

        $studentRole = Role::where('name', 'student')->first();

        $user->roles()->attach($studentRole);

        $student = Student::createStudent([
            'user_id' => $user->id,
            'internship_class_id' => $validatedData['internship_class_id'] ?? null,
            'study_program_id' => $validatedData['study_program_id'],
            'nim' => $validatedData['nim'],
            'telp' => $validatedData['telp'],
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student = $student;
        $studyPrograms = StudyProgram::all();
        $campuses = Campus::all();
        $classYears = ClassYear::all();
        
        return view('pages.admin.student.edit', compact('student', 'studyPrograms','campuses','classYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username,' . $student->user->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $student->user->id,
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'internship_class_id' => 'nullable|exists:internship_classes,id',
            'study_program_id' => 'required|exists:study_programs,id',
            'nim' => 'required|string|unique:students,nim,' . $student->id,
            'telp' => 'required|string',
        ]);

        $photoPath = $student->user->photo_profile_url;

        if ($request->hasFile('photo_profile')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        } 



        User::updateUser($student->user_id, [
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'photo_profile_url' => $photoPath,
        ]);

        Student::updateStudent($student->id, [
            'internship_class_id' => $validatedData['internship_class_id'] ?? null,
            'study_program_id' => $validatedData['study_program_id'],
            'nim' => $validatedData['nim'],
            'telp' => $validatedData['telp'],
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $userId = $student->user_id;
        $studentId = $student->id;


        // Hapus data setelah ID tersimpan
        User::deleteUser($userId);
        Student::deleteStudent($studentId);

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }

    public function changeStatus(Student $student)
    {
        $student->is_finished = !$student->is_finished;
        $student->save();
        return redirect()->route('admin.students.index')->with('success', 'Student status updated successfully');
    }

    public function filter(Request $request)
    {
        $query = Student::with(['certificate', 'user', 'studyProgram', 'internshipClass']);
        $studentCount = Student::count();
        $studyPrograms = StudyProgram::all();
        $campuses = Campus::all();
        $classYears = ClassYear::all();

        if ($request->filled('study_program')) {
            $query->whereHas('studyProgram', function ($q) use ($request) {
                $q->where('id', $request->study_program);
            });
        }

        if ($request->filled('class_year')) {
            $query->whereHas('internshipClass.classYear', function ($q) use ($request) {
                $q->where('class_year', $request->class_year);
            });
        }

        if ($request->filled('campus')) {
            $query->whereHas('studyProgram.campus', function ($q) use ($request) {
                $q->where('id', $request->campus);
            });
        }

        if ($request->filled('status')) {
            $query->where('is_finished', $request->status === 'finished');
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(10)->withQueryString();

        return view('pages.admin.student.index', compact('students', 'studentCount', 'studyPrograms','campuses','classYears'));
    }

    public function import(Request $request)
    {
        try {
            $validated = $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            $file = $request->file('file');

            // Baca file
            $rows = array_map('str_getcsv', file($file));

            if (empty($rows) || count($rows[0]) < 1) {
                return redirect()->back()->withErrors(['file' => 'File kosong atau tidak valid.']);
            }

            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            unset($rows[0]); // Hapus header

            $inserted = 0;
            $skipped = 0;
            $errorMessages = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    if (count($row) != count($headers)) {
                        $skipped++;
                        $errorMessages[] = "Baris ke-" . ($index + 2) . ": Jumlah kolom tidak cocok.";
                        continue; // Skip jika jumlah kolom tidak cocok
                    }

                    $row = array_combine($headers, $row);

                    if (!$row || empty($row['username']) || empty($row['nim']) || empty($row['email'])) {
                        $skipped++;
                        $errorMessages[] = "Baris ke-" . ($index + 2) . ": Data tidak lengkap (username, nim, atau email kosong).";
                        continue;
                    }

                    // Skip jika username, nim, atau email sudah ada
                    if (User::where('username', $row['username'])->exists()) {
                        $skipped++;
                        $errorMessages[] = "Baris ke-" . ($index + 2) . ": Username '{$row['username']}' sudah ada.";
                        continue;
                    }

                    if (Student::where('nim', $row['nim'])->exists()) {
                        $skipped++;
                        $errorMessages[] = "Baris ke-" . ($index + 2) . ": NIM '{$row['nim']}' sudah ada.";
                        continue;
                    }

                    if (User::where('email', $row['email'])->exists()) {
                        $skipped++;
                        $errorMessages[] = "Baris ke-" . ($index + 2) . ": Email '{$row['email']}' sudah ada.";
                        continue;
                    }

                    // Buat user
                    $user = User::addUser([
                        'username' => $row['username'],
                        'name' => $row['name'],
                        'email' => $row['email'] ?? null,
                        'password' => 'password',
                        'photo_profile_url' => null,
                    ]);

                    // Tambahkan role student
                    $studentRole = Role::where('name', 'student')->first();
                    if ($studentRole) {
                        $user->roles()->attach($studentRole);
                    }

                    // Buat student
                    Student::createStudent([
                        'user_id' => $user->id,
                        'internship_class_id' => !empty($row['internship_class_id']) ? intval($row['internship_class_id']) : null,
                        'study_program_id' => $row['study_program_id'],
                        'nim' => $row['nim'],
                        'telp' => $row['telp'],
                    ]);

                    $inserted++;
                } catch (\Throwable $e) {
                    $errorMessages[] = "Baris ke-" . ($index + 2) . ": Gagal diimport - " . $e->getMessage();
                    $skipped++;
                }
            }

            DB::commit();

            $errorMessageString = implode('<br>', $errorMessages);

            return redirect()->route('admin.students.index')->with('success', "Import selesai: {$inserted} data ditambahkan, {$skipped} dilewati karena duplikat atau error.<br>{$errorMessageString}");
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $e) {
            Log::error("Gagal mengimport file: " . $e->getMessage());
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat memproses file.' . $e->getMessage()])->withInput();
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_import_template.csv"',
        ];

        $columns = [
            'username',
            'name',
            'email',
            'study_program_id',
            'internship_class_id', // Optional
            'nim',
            'telp'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['johndoe', 'John Doe', 'john@example.com', '1', '', '123456789', '08123456789']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
