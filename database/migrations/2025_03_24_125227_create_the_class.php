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
        Schema::create('class_years', function (Blueprint $table) {
            $table->id();
            $table->string('class_year');
            $table->timestamps();
        });

        Schema::create('internship_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_year_id')->constrained('class_years')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_years');
        Schema::dropIfExists('internship_classes');
    }
};
