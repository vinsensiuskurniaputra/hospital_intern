<?php

namespace App\Models;

use App\Models\Campus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternshipClass extends Model
{
    use HasFactory;

    protected $fillable = ['class_year_id', 'name', 'description', 'campus_id'];

    public function classYear()
    {
        return $this->belongsTo(ClassYear::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}
