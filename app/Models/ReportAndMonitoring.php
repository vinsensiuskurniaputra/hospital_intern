<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAndMonitoring extends Model
{
    use HasFactory;

    // Gunakan tabel student_grades
    protected $table = 'student_grades';

    protected $fillable = [
        'fakultas',
        'kelompok',
        'kode',
        'jurusan',
        'universitas',
        'tahun',
        'status',
    ];
}