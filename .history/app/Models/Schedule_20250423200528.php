<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'internship_class_id',
        'stase',
        'departement_id',
        'responsible_user_id',
        'rotation_period_start',
        'rotation_period_end',
        'start_time',
        'end_time'
    ];

    public function internshipClass()
    {
        return $this->belongsTo(InternshipClass::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(ResponsibleUser::class);
    }
}
