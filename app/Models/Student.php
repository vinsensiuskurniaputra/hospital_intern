<?php

namespace App\Models;

use App\Models\User;
use App\Models\Presence;
use App\Models\Certificate;
use App\Models\StudentGrade;
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
        'is_finished'
    ];

    protected $casts = [
        'is_finished' => 'boolean'
    ];

    /**
     * Get the user that owns this student
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the internship class that owns this student
     */
    public function internshipClass()
    {
        return $this->belongsTo(InternshipClass::class);
    }

    /**
     * Get the study program that owns this student
     */
    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /**
     * Get the presences for this student
     */
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get the attendance excuses for this student
     */
    public function attendanceExcuses()
    {
        return $this->hasMany(AttendanceExcuse::class);
    }

    /**
     * Get the grades for this student
     */
    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    /**
     * Get the component grades for this student
     */
    public function componentGrades()
    {
        return $this->hasMany(StudentComponentGrade::class);
    }

    /**
     * Get the certificate for this student
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Get schedules through internship class
     */
    public function schedules()
    {
        return $this->hasManyThrough(
            Schedule::class,
            InternshipClass::class,
            'id', // Foreign key on internship_classes table
            'internship_class_id', // Foreign key on schedules table
            'internship_class_id', // Local key on students table
            'id' // Local key on internship_classes table
        );
    }

    // Static methods...
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
        $student = self::findOrFail($id);

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
}
