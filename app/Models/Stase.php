<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stase extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    /**
     * Get the departement that owns this stase
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    /**
     * Get the schedules for this stase
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the responsible users for this stase through pivot table
     * Using the ResponsibleStase model as pivot
     */
    public function responsibleUsers(): BelongsToMany
    {
        return $this->belongsToMany(ResponsibleUser::class, 'responsible_stase', 'stase_id', 'responsible_user_id');
    }

    /**
     * Get all responsible users with their user data
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
