<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternshipClass extends Model
{
    use HasFactory;

    protected $fillable = ['class_year_id', 'name', 'description'];

    public function classYear()
    {
        return $this->belongsTo(ClassYear::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
