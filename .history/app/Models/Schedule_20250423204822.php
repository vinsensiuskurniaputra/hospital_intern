<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'internship_class_id',
        'stase_id',
        'departement_id',
        'responsible_user_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time'
    ];

    protected $dates = [
        'start_date',
        'end_date',
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
        return $this->belongsTo(ResponsibleUser::class, 'responsible_user_id');
    }

    public function stase()
    {
        return $this->belongsTo(Stase::class, 'stase_id');
    }
}
