<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "filesystems.disks.public.url: " . config('filesystems.disks.public.url') . PHP_EOL;
echo "Storage url: " . Storage::disk('public')->url('66/award1.jpg') . PHP_EOL;

$ach = App\Models\Achievement::find(1);
$media = $ach->getFirstMedia('poster');
echo "Media getUrl(): " . $media->getUrl() . PHP_EOL;

