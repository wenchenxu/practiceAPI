<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password'); // Hash::make(...)
            $table->enum('role', ['hq', 'city_manager'])->default('city_manager');

            // IMPORTANT: create the column but DO NOT add a foreign key here,
            // because 'cities' doesn't exist yet when this migration runs.
            // $table->foreignId('city_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedBigInteger('city_id')->nullable();

            $table->timestamps();
            
            // You may add an index if you like:
            $table->index('city_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('users');
    }
};
