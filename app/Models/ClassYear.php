<?php

namespace App\Models;

use App\Models\InternshipClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassYear extends Model
{
    use HasFactory;

    protected $fillable = ['class_year'];

    public function internshipClasses()
    {
        return $this->hasMany(InternshipClass::class);
    }
}
