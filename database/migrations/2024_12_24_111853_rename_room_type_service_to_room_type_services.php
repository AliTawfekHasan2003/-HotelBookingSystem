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
        Schema::table('room_type_services', function (Blueprint $table) {
            Schema::rename('room_type_service', 'room_type_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_type_services', function (Blueprint $table) {
            Schema::rename('room_type_service', 'room_type_services');
        });
    }
};
