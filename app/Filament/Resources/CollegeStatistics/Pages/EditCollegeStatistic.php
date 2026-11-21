<?php

namespace App\Filament\Resources\CollegeStatistics\Pages;

use App\Filament\Resources\CollegeStatistics\CollegeStatisticResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCollegeStatistic extends EditRecord
{
    protected static string $resource = CollegeStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
