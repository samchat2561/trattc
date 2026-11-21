<?php

namespace App\Filament\Resources\TeacherCongratulations\Pages;

use App\Filament\Resources\TeacherCongratulations\TeacherCongratulationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeacherCongratulations extends ListRecords
{
    protected static string $resource = TeacherCongratulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
