<?php

namespace App\Filament\Resources\CollegeStatistics\Pages;

use App\Filament\Resources\CollegeStatistics\CollegeStatisticResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCollegeStatistics extends ListRecords
{
    protected static string $resource = CollegeStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('เพิ่มหัวข้อสถิติ'),
        ];
    }
}
