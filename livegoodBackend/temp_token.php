<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first();
if ($user) {
    echo $user->createToken('test')->plainTextToken;
} else {
    echo 'No user';
}
