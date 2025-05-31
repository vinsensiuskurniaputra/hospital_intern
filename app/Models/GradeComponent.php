<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'stase_id',
        'name'
    ];

    /**
     * Get the stase that owns this grade component
     */
    public function stase()
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get the student component grades for this component
     */
    public function studentComponentGrades()
    {
        return $this->hasMany(StudentComponentGrade::class);
    }
}
