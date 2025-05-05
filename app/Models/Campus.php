<?php

namespace App\Models;

use App\Models\StudyProgram;
use App\Models\InternshipClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'detail'];

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }
    public function internshipClasses()
    {
        return $this->hasMany(InternshipClass::class);
    }
}
