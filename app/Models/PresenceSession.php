<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceSession extends Model
{
    use HasFactory;
    
    protected $table = 'presence_sessions';
    
    protected $fillable = [
        'schedule_id',
        'token',
        'date',
        'expiration_time',
    ];
    
    // Ensure date fields are properly cast
    protected $casts = [
        'date' => 'date',
        'expiration_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the schedule that owns this presence session
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    /**
     * Get the presences for this session
     */
    public function presences()
    {
        return $this->hasMany(Presence::class, 'presence_sessions_id');
    }
    
    /**
     * Get the attendance excuses for this session
     */
    public function attendanceExcuses()
    {
        return $this->hasMany(AttendanceExcuse::class, 'presence_sessions_id');
    }
    
    /**
     * Check if the presence session is active
     */
    public function isActive(): bool
    {
        $now = now();
        return $now <= $this->expiration_time;
    }
}
