<?php

namespace App\Filament\Resources\CollegeStatistics;

use App\Filament\Resources\CollegeStatistics\Pages\CreateCollegeStatistic;
use App\Filament\Resources\CollegeStatistics\Pages\EditCollegeStatistic;
use App\Filament\Resources\CollegeStatistics\Pages\ListCollegeStatistics;
use App\Filament\Resources\CollegeStatistics\Schemas\CollegeStatisticForm;
use App\Filament\Resources\CollegeStatistics\Tables\CollegeStatisticsTable;
use App\Models\CollegeStatistic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CollegeStatisticResource extends Resource
{
    protected static ?string $model = CollegeStatistic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartPie;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ChartPie;
    protected static ?string $recordTitleAttribute = 'title';
    protected static \UnitEnum|string|null $navigationGroup = 'ข้อมูลสถานศึกษา';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'ข้อมูลสถิติสถานศึกษา';
    }

    public static function getModelLabel(): string
    {
        return 'ข้อมูลสถิติสถานศึกษา';
    }

    public static function getPluralModelLabel(): string
    {
        return 'ข้อมูลสถิติสถานศึกษา';
    }

    public static function form(Schema $schema): Schema
    {
        return CollegeStatisticForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CollegeStatisticsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCollegeStatistics::route('/'),
            'create' => CreateCollegeStatistic::route('/create'),
            'edit' => EditCollegeStatistic::route('/{record}/edit'),
        ];
    }
}
