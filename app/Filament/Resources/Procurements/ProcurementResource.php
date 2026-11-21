<?php

namespace App\Filament\Resources\Procurements;

use App\Filament\Resources\Procurements\Pages\CreateProcurement;
use App\Filament\Resources\Procurements\Pages\EditProcurement;
use App\Filament\Resources\Procurements\Pages\ListProcurements;
use App\Filament\Resources\Procurements\Schemas\ProcurementForm;
use App\Filament\Resources\Procurements\Tables\ProcurementsTable;
use App\Models\Procurement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProcurementResource extends Resource
{
    protected static ?string $model = Procurement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCurrencyDollar;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::DocumentCurrencyDollar;
    protected static ?string $recordTitleAttribute = 'title';
    protected static \UnitEnum|string|null $navigationGroup = 'งานพัสดุและจัดซื้อจัดจ้าง';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'ประกาศจัดซื้อ-จัดจ้าง';
    }

    public static function getModelLabel(): string
    {
        return 'ประกาศจัดซื้อ-จัดจ้าง';
    }

    public static function getPluralModelLabel(): string
    {
        return 'ประกาศจัดซื้อ-จัดจ้าง';
    }

    public static function form(Schema $schema): Schema
    {
        return ProcurementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcurementsTable::configure($table);
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
            'index' => ListProcurements::route('/'),
            'create' => CreateProcurement::route('/create'),
            'edit' => EditProcurement::route('/{record}/edit'),
        ];
    }
}
