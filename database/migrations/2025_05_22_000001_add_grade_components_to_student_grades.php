<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->integer('keahlian')->default(0);
            $table->integer('profesionalisme')->default(0);
            $table->integer('komunikasi')->default(0);
            $table->integer('kemampuan_pasien')->default(0);
            $table->text('comment')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn(['keahlian', 'profesionalisme', 'komunikasi', 'kemampuan_pasien', 'comment']);
        });
    }
};