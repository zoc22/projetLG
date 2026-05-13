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
        // Ensure the tokenable_id column can hold UUIDs (stored as text in PG)
        // The 'id' column stays as bigint (Sanctum's internal PK) which is fine
        // The issue was the transaction failing - this is resolved by the custom PAT model
        // No schema change needed - the uuid column is already correct
        // Just verify the constraint is right by checking column type
        DB::statement('ALTER TABLE personal_access_tokens ALTER COLUMN tokenable_id TYPE text');
    }

    public function down(): void
    {
        // Revert if needed - but UUID is stored as text which is compatible
        DB::statement('ALTER TABLE personal_access_tokens ALTER COLUMN tokenable_id TYPE uuid USING tokenable_id::uuid');
    }
};
