<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_component_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('grade_component_id')->constrained('grade_components')->onDelete('cascade');
            $table->foreignId('stase_id')->constrained('stases')->onDelete('cascade');
            $table->decimal('value', 5, 2); // Store grade value with 2 decimal places
            $table->date('evaluation_date'); // Date when this grade was given
            $table->foreignId('responsible_user_id')->constrained('users'); // Who gave this grade
            $table->timestamps();
            
            // Use a shorter custom index name
            $table->index(['student_id', 'grade_component_id', 'stase_id'], 'scg_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_component_grades');
    }
};
