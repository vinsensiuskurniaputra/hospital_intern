<?php

namespace App\Models;

use App\Models\ResponsibleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stase extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'responsible_user_id', 'detail'];

    protected $guarded = ['id'];

    public function responsibleUser()
    {
        return $this->belongsTo(ResponsibleUser::class);
    }


    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function gradeComponents()
    {
        return $this->hasMany(GradeComponent::class);
    }

    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}
