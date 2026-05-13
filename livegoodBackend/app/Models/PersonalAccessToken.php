<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * PersonalAccessToken with UUID support.
 * Overrides Sanctum's default model to support UUID primary keys.
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    // The default primary key 'id' is a bigint auto-increment.
    // 'tokenable_id' is a UUID in the database, which morphTo handles automatically.
}
