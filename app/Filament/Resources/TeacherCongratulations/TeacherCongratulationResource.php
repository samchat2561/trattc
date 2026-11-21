<?php

namespace App\Filament\Resources\TeacherCongratulations;

use App\Filament\Resources\TeacherCongratulations\Pages\CreateTeacherCongratulation;
use App\Filament\Resources\TeacherCongratulations\Pages\EditTeacherCongratulation;
use App\Filament\Resources\TeacherCongratulations\Pages\ListTeacherCongratulations;
use App\Filament\Resources\TeacherCongratulations\Schemas\TeacherCongratulationForm;
use App\Filament\Resources\TeacherCongratulations\Tables\TeacherCongratulationsTable;
use App\Models\TeacherCongratulation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeacherCongratulationResource extends Resource
{
    protected static ?string $model = TeacherCongratulation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::AcademicCap;
    protected static ?string $recordTitleAttribute = 'title';
    protected static \UnitEnum|string|null $navigationGroup = 'ข้อมูลบุคลากร';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'แสดงความยินดี (บุคลากร)';
    }

    public static function getModelLabel(): string
    {
        return 'แสดงความยินดี';
    }

    public static function getPluralModelLabel(): string
    {
        return 'แสดงความยินดี คณะครูและบุคลากร';
    }

    public static function form(Schema $schema): Schema
    {
        return TeacherCongratulationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherCongratulationsTable::configure($table);
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
            'index' => ListTeacherCongratulations::route('/'),
            'create' => CreateTeacherCongratulation::route('/create'),
            'edit' => EditTeacherCongratulation::route('/{record}/edit'),
        ];
    }
}
