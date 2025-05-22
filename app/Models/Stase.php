<?php

namespace App\Models;

use App\Models\ResponsibleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stase extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'responsible_user_id', 'departement_id', 'detail'];

    protected $guarded = ['id'];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function gradeComponents()
    {
        return $this->hasMany(GradeComponent::class);
    }

    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function responsibleStases()
    {
        return $this->hasMany(ResponsibleStase::class);
    }

    /**
     * Get all student component grades for this stase
     */
    public function studentComponentGrades()
    {
        return $this->hasMany(StudentComponentGrade::class);
    }
}
