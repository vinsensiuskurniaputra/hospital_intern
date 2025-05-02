<?php

namespace App\Models;

use App\Models\Stase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
    protected $guarded = ['id'];

    public function stases()
    {
        return $this->hasMany(Stase::class);
    }

    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}
