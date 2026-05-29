<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    $user = User::first();
    auth()->login($user);
    $response = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle(Request::create('/interview', 'GET'));
    echo $response->getContent();
} catch (Throwable $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
    echo $e->getFile().':'.$e->getLine()."\n";
    echo $e->getTraceAsString();
}
