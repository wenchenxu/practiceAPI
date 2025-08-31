<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the FK now that 'cities' table exists
            $table->foreign('city_id')
                  ->references('id')->on('cities')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });
    }
};
