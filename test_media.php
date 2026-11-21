<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$images = \App\Models\Image::with('media')->get();
foreach($images as $img) {
    echo "ID: {$img->id}, Title: {$img->title}\n";
    $media = $img->getMedia('images');
    echo "Media count: " . $media->count() . "\n";
    if ($media->count() > 0) {
        echo "URL: " . $media->first()->getUrl() . "\n";
    }
    echo "-------------------\n";
}
