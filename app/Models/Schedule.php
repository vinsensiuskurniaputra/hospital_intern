<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'stase_id',
        'internship_class_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function stase(): BelongsTo
    {
        return $this->belongsTo(Stase::class);
    }

    public function internshipClass(): BelongsTo
    {
        return $this->belongsTo(InternshipClass::class);
    }

    public function responsibleUsers(): BelongsToMany
    {
        return $this->belongsToMany(ResponsibleUser::class, 'responsible_schedule');
    }
}
