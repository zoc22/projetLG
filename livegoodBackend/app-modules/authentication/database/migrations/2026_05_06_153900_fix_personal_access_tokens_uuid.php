<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Fix personal_access_tokens table to support UUID tokenable_id properly.
 * The default Sanctum migration uses bigint id - we need to keep that 
 * but ensure tokenable_id is stored as text (UUID-compatible) in PostgreSQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE personal_access_tokens ALTER COLUMN tokenable_id TYPE text');
        } else {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->string('tokenable_id')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE personal_access_tokens ALTER COLUMN tokenable_id TYPE uuid USING tokenable_id::uuid');
        } else {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->unsignedBigInteger('tokenable_id')->change();
            });
        }
    }
};
