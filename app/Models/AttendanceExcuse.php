<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceExcuse extends Model
{
    use HasFactory;
    
    protected $table = 'attendance_excuse';
    protected $guarded = ['id'];
    
    /**
     * Get the student that owns the excuse
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the presence session for this excuse
     */
    public function presenceSession(): BelongsTo
    {
        return $this->belongsTo(PresenceSession::class, 'presence_sessions_id');
    }
}
