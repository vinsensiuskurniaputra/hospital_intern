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
        Schema::create('stases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsible_user_id')->constrained('responsible_users')->onDelete('cascade');
            $table->string('name');
            $table->string('detai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stases');
    }
};
