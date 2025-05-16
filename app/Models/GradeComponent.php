<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeComponent extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    /**
     * Get the stase that owns this grade component
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }
    /**
     * Get the student grades for this component
     */
    public function studentGrades()
    {
        return $this->hasMany(StudentComponentGrade::class);
    }
}
