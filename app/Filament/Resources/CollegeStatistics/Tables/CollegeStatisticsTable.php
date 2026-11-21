<?php

namespace App\Filament\Resources\CollegeStatistics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollegeStatisticsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('ลำดับ')
                    ->numeric()
                    ->sortable()
                    ->width('60px'),

                TextColumn::make('title')
                    ->label('หัวข้อสถิติ')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->subtitle),

                TextColumn::make('code')
                    ->label('รหัสอ้างอิง')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('chart_type')
                    ->label('รูปแบบกราฟ')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'doughnut' => '🍩 โดนัท',
                        'pie' => '🥧 พาย',
                        'bar' => '📊 แท่งแนวตั้ง',
                        'horizontal-bar' => '📊 แท่งแนวนอน',
                        'polarArea' => '🎯 โพลาร์',
                        default => $state,
                    })
                    ->color('info'),

                TextColumn::make('total_count')
                    ->label('ยอดรวม')
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => number_format($state) . ' ' . $record->unit)
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('items_count')
                    ->label('รายการย่อย')
                    ->state(fn ($record) => is_array($record->items) ? count($record->items) . ' รายการ' : '0 รายการ')
                    ->badge()
                    ->color('secondary'),

                IconColumn::make('is_active')
                    ->label('สถานะ')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('อัพเดตเมื่อ')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
