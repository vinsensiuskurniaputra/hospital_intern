<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'stase_id',
        'internship_class_id', 
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the stase that owns this schedule
     */
    public function stase()
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get the internship class that owns this schedule
     */
    public function internshipClass()
    {
        return $this->belongsTo(InternshipClass::class);
    }

    /**
     * Get the responsible users for this schedule through the stase
     * Using the ResponsibleStase pivot model
     */
    public function responsibleUsers()
    {
        return $this->hasManyThrough(
            ResponsibleUser::class,
            ResponsibleStase::class,
            'stase_id', // Foreign key on responsible_stase table
            'id', // Foreign key on responsible_users table
            'stase_id', // Local key on schedules table
            'responsible_user_id' // Local key on responsible_stase table
        );
    }

    /**
     * Get responsible assignments for this schedule's stase
     */
    public function responsibleAssignments()
    {
        return $this->hasMany(ResponsibleStase::class, 'stase_id', 'stase_id');
    }

    /**
     * Get the primary responsible user for this schedule's stase
     */
    public function primaryResponsibleUser()
    {
        $assignment = $this->responsibleAssignments()->with('responsibleUser.user')->first();
        return $assignment ? $assignment->responsibleUser : null;
    }

    /**
     * Get all responsible users with their user data for this schedule
     */
    public function getAllResponsibleUsers()
    {
        return $this->responsibleAssignments()
                   ->with('responsibleUser.user')
                   ->get()
                   ->map(function($assignment) {
                       return $assignment->responsibleUser;
                   });
    }
}
