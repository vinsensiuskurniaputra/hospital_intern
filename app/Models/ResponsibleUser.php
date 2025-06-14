<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResponsibleUser extends Model
{
    use HasFactory;

    protected $table = 'responsible_users';

    protected $fillable = [
        'user_id',
        'telp',
        'nip',
        'specialization',
        'status',
        'employee_id',
        'specialization',
        'phone_number',
        'department'
    ];

    /**
     * Get the user that owns this responsible user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stases that this responsible user is responsible for
     * Using the ResponsibleStase model as pivot
     */
    public function stases(): BelongsToMany
    {
        return $this->belongsToMany(Stase::class, 'responsible_stase', 'responsible_user_id', 'stase_id');
    }

    /**
     * Get responsible assignments for this user
     */
    public function responsibleAssignments()
    {
        return $this->hasMany(ResponsibleStase::class);
    }

    /**
     * Get schedules through stases relationship
     */
    public function schedules()
    {
        return $this->hasManyThrough(
            Schedule::class,
            ResponsibleStase::class,
            'responsible_user_id', // Foreign key on responsible_stase table
            'stase_id', // Foreign key on schedules table
            'id', // Local key on responsible_users table
            'stase_id' // Local key on responsible_stase table
        );
    }

    /**
     * Get all stases with their details that this user is responsible for
     */
    public function getAllStases()
    {
        return $this->responsibleAssignments()
                   ->with('stase.departement')
                   ->get()
                   ->map(function($assignment) {
                       return $assignment->stase;
                   });
    }

    /**
     * Check if this user is responsible for a specific stase
     */
    public function isResponsibleFor($staseId)
    {
        return $this->responsibleAssignments()
                   ->where('stase_id', $staseId)
                   ->exists();
    }

    /**
     * Assign this responsible user to a stase
     */
    public function assignToStase($staseId)
    {
        return ResponsibleStase::assignResponsibleToStase($this->id, $staseId);
    }

    /**
     * Remove assignment from a stase
     */
    public function removeFromStase($staseId)
    {
        return ResponsibleStase::removeAssignment($this->id, $staseId);
    }

    public static function createResponsibleUser($data)
    {
        return self::create([
            'user_id' => $data['user_id'],
            'telp' => $data['telp'],
        ]);
    }

    public static function updateResponsibleUser($id, $data)
    {
        $responsibleUser = self::findOrFail($id);

        $responsibleUser->update([
            'user_id' => $data['user_id'] ?? $responsibleUser->user_id,
            'telp' => $data['telp'] ?? $responsibleUser->telp,
        ]);

        return $responsibleUser;
    }

    public static function deleteResponsibleUser($id)
    {
        // Remove all assignments first
        ResponsibleStase::where('responsible_user_id', $id)->delete();
        
        // Then delete the responsible user
        self::where('id', $id)->delete();
    }
}
