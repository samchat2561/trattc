<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$session = DB::table('sessions')->where('id', 'iuuLqT5DSGOw7WuT3bfn4dAEmNfNX6AGQQMmZd5Y')->first();
$data = base64_decode($session->payload);
// Search for URL inside payload
preg_match_all('/https?:\/\/[^\s"\'\\\]+/', $data, $matches);
echo "URLs found in session payload:" . PHP_EOL;
print_r(array_unique($matches[0]));
