<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    /**
     * Get the student that owns this presence record
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the presence session for this presence record
     */
    public function presenceSession(): BelongsTo
    {
        return $this->belongsTo(PresenceSession::class, 'presence_sessions_id');
    }
}
