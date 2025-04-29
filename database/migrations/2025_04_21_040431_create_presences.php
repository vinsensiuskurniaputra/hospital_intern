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
        Schema::create('presence_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('token');
            $table->date('date'); // Sesuai dengan ERD: date
            $table->time('start_time'); // Sesuai dengan ERD: start_time
            $table->time('end_time'); // Sesuai dengan ERD: end_time
            $table->timestamps();
        });

        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('presence_sessions_id')->constrained('presence_sessions');
            $table->date('date_entry');
            $table->time('check_in');
            $table->time('check_out');
            $table->string('status')->default('present');
            $table->timestamps();
        });

        Schema::create('attendance_excuse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('presence_sessions_id')->constrained('presence_sessions')->onDelete('cascade'); // Perbaiki foreign key constraint
            $table->string('detail');
            $table->string('letter_url')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_excuse');
        Schema::dropIfExists('presences');
        Schema::dropIfExists('presence_sessions');
    }
};
