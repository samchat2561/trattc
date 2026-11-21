<?php

namespace App\Filament\Resources\TeacherCongratulations\Pages;

use App\Filament\Resources\TeacherCongratulations\TeacherCongratulationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherCongratulation extends CreateRecord
{
    protected static string $resource = TeacherCongratulationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
