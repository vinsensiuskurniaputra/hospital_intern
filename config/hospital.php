<?php
// filepath: d:\Folder Utama\Documents\Kuliah\Semester 4\PBL\hospital_intern\config\hospital.php

return [
    /*
    |--------------------------------------------------------------------------
    | Hospital Location Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines the hospital's location and attendance radius
    | for student attendance validation.
    |
    */

    'name' => env('HOSPITAL_NAME', 'RSUD Dr. Adhyatma, MPH Tugurejo'),
    
    'coordinates' => [
        'latitude' => env('HOSPITAL_LATITUDE', -6.984791715609357),
        'longitude' => env('HOSPITAL_LONGITUDE', 110.35529557537339),
    ],
    
    'attendance_radius' => env('HOSPITAL_RADIUS', 500), // in meters
];