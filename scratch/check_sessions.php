<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sessions = DB::table('sessions')->orderBy('last_activity', 'desc')->take(3)->get();
foreach ($sessions as $s) {
    echo "ID: {$s->id} | User: {$s->user_id} | IP: {$s->ip_address} | Last Activity: " . date('Y-m-d H:i:s', $s->last_activity) . PHP_EOL;
    $payload = @unserialize(base64_decode($s->payload));
    if (is_array($payload)) {
        echo "Keys: " . implode(', ', array_keys($payload)) . PHP_EOL;
        if (isset($payload['_previous'])) {
            echo "Previous URL: " . json_encode($payload['_previous']) . PHP_EOL;
        }
    }
}

