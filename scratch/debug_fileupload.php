<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
auth()->login($user);

$record = App\Models\Achievement::find(1);
$form = App\Filament\Resources\Achievements\Pages\EditAchievement::class;
$component = Livewire\Livewire::test($form, ['record' => 1]);

$field = null;
foreach ($component->instance()->form->getComponents() as $c) {
    // Traverse form components
    echo get_class($c) . PHP_EOL;
}

