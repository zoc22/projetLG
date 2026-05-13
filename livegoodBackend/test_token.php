<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \Modules\Authentication\Models\User::first();
if (!$user) {
    echo "No user found.\n";
    exit;
}
echo "User: " . $user->email . "\n";
$token = $user->createToken("test");
echo "Token string: " . $token->plainTextToken . "\n";
$resolvedToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token->plainTextToken);
if (!$resolvedToken) {
    echo "Token could not be resolved from plain text!\n";
    exit;
}
echo "Resolved Token ID: " . $resolvedToken->id . "\n";
$resolvedUser = $resolvedToken->tokenable;
echo "Resolved User ID: " . ($resolvedUser ? $resolvedUser->id : "NULL") . "\n";
