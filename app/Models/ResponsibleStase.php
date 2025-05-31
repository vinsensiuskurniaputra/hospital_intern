<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponsibleStase extends Model
{
    use HasFactory;

    protected $table = 'responsible_stase';

    protected $fillable = [
        'responsible_user_id',
        'stase_id'
    ];

    /**
     * Get the responsible user that owns this assignment
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(ResponsibleUser::class);
    }

    /**
     * Get the stase that owns this assignment
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get schedules for this specific responsible user and stase combination
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'stase_id', 'stase_id');
    }

    /**
     * Static method to assign responsible user to stase
     */
    public static function assignResponsibleToStase($responsibleUserId, $staseId)
    {
        return self::firstOrCreate([
            'responsible_user_id' => $responsibleUserId,
            'stase_id' => $staseId
        ]);
    }

    /**
     * Static method to remove assignment
     */
    public static function removeAssignment($responsibleUserId, $staseId)
    {
        return self::where('responsible_user_id', $responsibleUserId)
                  ->where('stase_id', $staseId)
                  ->delete();
    }

    /**
     * Get all assignments for a responsible user
     */
    public static function getStasesForResponsible($responsibleUserId)
    {
        return self::where('responsible_user_id', $responsibleUserId)
                  ->with('stase')
                  ->get();
    }

    /**
     * Get all assignments for a stase
     */
    public static function getResponsiblesForStase($staseId)
    {
        return self::where('stase_id', $staseId)
                  ->with('responsibleUser.user')
                  ->get();
    }
}