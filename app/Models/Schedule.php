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
        'start_date',
        'end_date'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function internshipClass()
    {
        return $this->belongsTo(InternshipClass::class);
    }

    public function stase()
    {
        return $this->belongsTo(Stase::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
}
