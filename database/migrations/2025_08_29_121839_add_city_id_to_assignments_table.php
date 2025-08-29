<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('driver_id')->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->index(['city_id', 'assigned_at']);
        });
    }
    public function down(): void {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex(['city_id', 'assigned_at']);
            $table->dropConstrainedForeignId('city_id');
        });
    }
};
