<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
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
    
    /**
     * Get the department for this grade
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
