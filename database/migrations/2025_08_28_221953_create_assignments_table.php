<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->timestampTz('assigned_at')->useCurrent();
            $table->timestampTz('released_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'assigned_at']);
            $table->index(['driver_id', 'assigned_at']);
        });

        // Enforce: only one ACTIVE assignment per vehicle and per driver.
        // (Postgres partial unique indexes)
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("CREATE UNIQUE INDEX assignments_one_active_per_vehicle
                           ON assignments (vehicle_id)
                           WHERE released_at IS NULL");
            DB::statement("CREATE UNIQUE INDEX assignments_one_active_per_driver
                           ON assignments (driver_id)
                           WHERE released_at IS NULL");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("DROP INDEX IF EXISTS assignments_one_active_per_vehicle");
            DB::statement("DROP INDEX IF EXISTS assignments_one_active_per_driver");
        }
        Schema::dropIfExists('assignments');
    }
};
