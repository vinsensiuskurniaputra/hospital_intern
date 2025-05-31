<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'evaluation_date' => 'date',
        'responsible_user_id' => 'integer'
    ];

    /**
     * Get the student that owns this grade
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the grade component for this grade
     */
    public function gradeComponent()
    {
        return $this->belongsTo(GradeComponent::class);
    }

    /**
     * Get the stase for this grade
     */
    public function stase()
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get the responsible user who gave this grade
     */
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
