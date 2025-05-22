<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponsibleStase extends Model
{
    use HasFactory;

    protected $table = 'responsible_stase';

    protected $fillable = [
        'stase_id',
        'responsible_user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the stase associated with the responsible stase
     */
    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }

    /**
     * Get the responsible user associated with the responsible stase
     */
    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(ResponsibleUser::class);
    }

    /**
     * Get the user through responsible user
     */
    public function user()
    {
        return $this->responsibleUser->user();
    }
}
