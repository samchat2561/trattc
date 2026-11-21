<?php

namespace App\Filament\Resources\TeacherCongratulations\Pages;

use App\Filament\Resources\TeacherCongratulations\TeacherCongratulationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacherCongratulation extends EditRecord
{
    protected static string $resource = TeacherCongratulationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
