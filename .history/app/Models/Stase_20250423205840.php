<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stase extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'responsible_user_id'];

    public function responsibleUser()
    {
        return $this->belongsTo(ResponsibleUser::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
