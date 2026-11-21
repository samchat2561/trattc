<?php

namespace App\Filament\Resources\Newsletters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewslettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('หน้าปก')
                    ->disk('public')
                    ->defaultImageUrl(asset('images/newsletters/newsletter_default.svg'))
                    ->square(),

                TextColumn::make('title')
                    ->label('หัวข้อจดหมายข่าว')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title),

                TextColumn::make('issue_no')
                    ->label('ฉบับที่')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('หมวดหมู่')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('วันที่เผยแพร่')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('attachments')
                    ->label('ไฟล์แนบ')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' ไฟล์' : '0 ไฟล์')
                    ->badge()
                    ->color('warning'),

                IconColumn::make('is_active')
                    ->label('แสดงผล')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('ยอดเข้าชม')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('uploader_name')
                    ->label('ผู้อัปโหลด')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sort_order')
                    ->label('ลำดับ')
                    ->numeric()
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
