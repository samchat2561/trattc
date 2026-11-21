<?php

namespace App\Filament\Resources\Achievements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AchievementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('poster')
                    ->label('รูปโปสเตอร์')
                    ->collection('poster')
                    ->square(),

                TextColumn::make('title')
                    ->label('ชื่อรางวัล / ผลงานความสำเร็จ')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('position')
                    ->label('ผู้ได้รับรางวัล / สาขาวิชา')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('published_at')
                    ->label('วันที่ประกาศ')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('ยอดเข้าชม')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('uploader_name')
                    ->label('ผู้อัพโหลด')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                IconColumn::make('is_active')
                    ->label('สถานะ')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('ลำดับ')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('สร้างเมื่อ')
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

