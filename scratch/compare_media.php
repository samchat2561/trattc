<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\TeacherCongratulation::first();
$mediaT = $teacher->getFirstMedia('poster');

$ach = App\Models\Achievement::first();
$mediaA = $ach->getFirstMedia('poster');

echo "Teacher Media:" . PHP_EOL;
echo " - ID: " . $mediaT->id . PHP_EOL;
echo " - UUID: " . $mediaT->uuid . PHP_EOL;
echo " - Path: " . $mediaT->getPath() . PHP_EOL;
echo " - URL: " . $mediaT->getUrl() . PHP_EOL;
echo " - Custom Properties: " . json_encode($mediaT->custom_properties) . PHP_EOL;
echo " - Responsive: " . json_encode($mediaT->responsive_images) . PHP_EOL;
echo " - Conversions: " . json_encode($mediaT->generated_conversions) . PHP_EOL;

echo PHP_EOL . "Achievement Media:" . PHP_EOL;
echo " - ID: " . $mediaA->id . PHP_EOL;
echo " - UUID: " . $mediaA->uuid . PHP_EOL;
echo " - Path: " . $mediaA->getPath() . PHP_EOL;
echo " - URL: " . $mediaA->getUrl() . PHP_EOL;
echo " - Custom Properties: " . json_encode($mediaA->custom_properties) . PHP_EOL;
echo " - Responsive: " . json_encode($mediaA->responsive_images) . PHP_EOL;
echo " - Conversions: " . json_encode($mediaA->generated_conversions) . PHP_EOL;

