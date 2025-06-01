<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'stase_id',
        'student_id',
        'avg_grades'
    ];

    protected $casts = [
        'avg_grades' => 'integer'
    ];

    /**
     * Get the stase that owns this grade
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get the student that owns this grade
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the departement through the stase relationship
     */
    public function departement()
    {
        return $this->hasOneThrough(
            Departement::class,
            Stase::class,
            'id', // Foreign key on stases table
            'id', // Foreign key on departements table
            'stase_id', // Local key on student_grades table
            'departement_id' // Local key on stases table
        );
    }
}
