<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Postgres: use raw statements; avoids requiring doctrine/dbal for change()
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vehicles ALTER COLUMN driver_name DROP NOT NULL');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN driver_phone_number DROP NOT NULL');
        } else {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('driver_name', 100)->nullable()->change();
                $table->string('driver_phone_number', 25)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vehicles ALTER COLUMN driver_name SET NOT NULL');
            DB::statement('ALTER TABLE vehicles ALTER COLUMN driver_phone_number SET NOT NULL');
        } else {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('driver_name', 100)->nullable(false)->change();
                $table->string('driver_phone_number', 25)->nullable(false)->change();
            });
        }
    }
};
