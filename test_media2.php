<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$media = \Spatie\MediaLibrary\MediaCollections\Models\Media::all();
foreach($media as $m) {
    echo "ID: {$m->id}, Model ID: {$m->model_id}, Disk: {$m->disk}, Path: {$m->getPath()}, URL: {$m->getUrl()}\n";
}
