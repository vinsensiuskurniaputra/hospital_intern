<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    public $timestamps = true; 
    
    /**
     * Get the student that owns this grade
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the stase for this grade
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }

}
