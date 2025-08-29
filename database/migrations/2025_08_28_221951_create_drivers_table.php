<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 25)->nullable();
            $table->string('license_number', 50)->unique();
            $table->date('license_expiry')->nullable();
            $table->string('status', 20)->default('active'); // later: promote to PHP Enum
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
