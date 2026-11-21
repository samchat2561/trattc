<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;

$medias = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('disk', 'local')->get();
foreach($medias as $media) {
    // move directory
    $oldDir = __DIR__ . '/storage/app/private/' . $media->id;
    $newDir = __DIR__ . '/storage/app/public/' . $media->id;
    if (is_dir($oldDir)) {
        File::copyDirectory($oldDir, $newDir);
        File::deleteDirectory($oldDir);
        echo "Moved $oldDir to $newDir\n";
    }
    $media->disk = 'public';
    $media->save();
}
echo "Done fixing existing media.";
