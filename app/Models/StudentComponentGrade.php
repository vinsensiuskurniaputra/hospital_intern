<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentComponentGrade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'grade_component_id',
        'stase_id',
        'value',
        'evaluation_date',
        'responsible_user_id'
    ];
    
    /**
     * Get the student that owns this grade
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the grade component this grade belongs to
     */
    public function gradeComponent(): BelongsTo
    {
        return $this->belongsTo(GradeComponent::class);
    }
    
    /**
     * Get the stase this grade belongs to
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }
    
    /**
     * Get the responsible user who gave this grade
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
    
    /**
     * Scope to get the latest grade for each component
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('evaluation_date', 'desc');
    }
}
