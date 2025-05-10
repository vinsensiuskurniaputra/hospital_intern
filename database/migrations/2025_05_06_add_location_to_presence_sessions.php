<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presence_sessions', function (Blueprint $table) {
            $table->timestamp('expiration_time')->nullable()->after('token');
            $table->dropColumn(['start_time', 'end_time']);
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('status');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('device_info')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('presence_sessions', function (Blueprint $table) {
            $table->dropColumn('expiration_time');
            $table->time('start_time')->nullable(); 
            $table->time('end_time')->nullable();   
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'device_info']);
        });
    }
};