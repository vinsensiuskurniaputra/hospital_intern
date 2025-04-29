<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresenceSession extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'schedule_id',
        'token',
        'date',
        'start_time',
        'end_time'
    ];
    
    /**
     * Get the schedule that owns this presence session
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
    
    /**
     * Get the presences for this session
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'presence_sessions_id');
    }
    
    /**
     * Get the attendance excuses for this session
     */
    public function attendanceExcuses(): HasMany
    {
        return $this->hasMany(AttendanceExcuse::class, 'presence_sessions_id');
    }
    
    /**
     * Check if the presence session is active
     */
    public function isActive(): bool
    {
        $now = now();
        $sessionDate = $this->date;
        $startTime = $this->start_time;
        $endTime = $this->end_time;
        
        $sessionStart = \Carbon\Carbon::parse("$sessionDate $startTime");
        $sessionEnd = \Carbon\Carbon::parse("$sessionDate $endTime");
        
        return $now->between($sessionStart, $sessionEnd);
    }
}
