<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'kode', 'certificate_url'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
