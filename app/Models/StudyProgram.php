<?php

namespace App\Models;

use App\Models\Campus;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyProgram extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'campus_id'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
