<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Media for Achievement:" . PHP_EOL;
$media = Spatie\MediaLibrary\MediaCollections\Models\Media::where('model_type', 'App\Models\Achievement')->get();
foreach ($media as $m) {
    echo "ID: {$m->id} | Model ID: {$m->model_id} | File: {$m->file_name} | Disk: {$m->disk} | Size: {$m->size} | Created: {$m->created_at} | Updated: {$m->updated_at}" . PHP_EOL;
}

