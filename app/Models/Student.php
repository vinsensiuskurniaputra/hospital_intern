<?php

namespace App\Models;

use App\Models\User;
use App\Models\Certificate;
use App\Models\StudyProgram;
use App\Models\InternshipClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_class_id',
        'study_program_id',
        'nim',
        'telp',
        'is_finished',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internshipClass()
    {
        return $this->belongsTo(InternshipClass::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public static function createStudent($data)
    {
        return self::create([
            'user_id' => $data['user_id'],
            'internship_class_id' => $data['internship_class_id'] ?? null,
            'study_program_id' => $data['study_program_id'],
            'nim' => $data['nim'],
            'telp' => $data['telp'],
            'is_finished' => $data['is_finished'] ?? false,
        ]);
    }

    public static function updateStudent($id, $data)
    {
        $student = self::findOrFail($id); // Cari student berdasarkan ID

        $student->update([
            'user_id' => $data['user_id'] ?? $student->user_id,
            'internship_class_id' => $data['internship_class_id'] ?? $student->internship_class_id,
            'study_program_id' => $data['study_program_id'] ?? $student->study_program_id,
            'nim' => $data['nim'] ?? $student->nim,
            'telp' => $data['telp'] ?? $student->telp,
            'is_finished' => $data['is_finished'] ?? $student->is_finished,
        ]);

        return $student;
    }

    public static function deleteStudent($id)
    {
        self::where('id', $id)->delete();
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function attendanceExcuses()
    {
        return $this->hasMany(AttendanceExcuse::class);
    }
    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function schedules()
    {
        return $this->hasManyThrough(
            Schedule::class,
            InternshipClass::class,
            'id', // Foreign key pada internship_class yang menunjuk ke local key di tabel student
            'internship_class_id', // Foreign key pada schedules yang menunjuk ke primary key di tabel internship_class
            'internship_class_id', // Local key pada student
            'id' // Primary key pada internship_class
        );
    }

}
