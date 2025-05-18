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
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->string('name');
            $table->string('detail')->nullable();
            $table->timestamps();
        });
        Schema::create('responsible_stase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsible_user_id')->nullable()->constrained('responsible_users')->onDelete('set null');
            $table->foreignId('stase_id')->constrained('stases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stases');
        Schema::dropIfExists('responsible_stase');
    }
};
