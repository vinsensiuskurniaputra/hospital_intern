<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAndMonitoring extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    /**
     * Get the student this report is about
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the responsible user who created this report
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(ResponsibleUser::class);
    }
}
