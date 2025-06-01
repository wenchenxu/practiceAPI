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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BigInt primary key: 'ID'
            $table->string('license_number')->unique(); // For 'license number', making it unique
            $table->string('driver_name'); // For 'driver name'
            $table->string('driver_phone_number')->nullable(); // For 'driver phone number', nullable if it might be empty sometimes
            $table->timestamps(); // Creates 'created_at' (your creation time) and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
