<?php

namespace App\Filament\Resources\Images\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;

class ImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn (TextColumn $column): string => $column->getState()), // เมื่อเอาเมาส์ชี้ จะแสดง

                TextColumn::make('slug')
                    ->searchable()
                    ->limit(10)
                    ->tooltip(fn (TextColumn $column): string => $column->getState()),

                SpatieMediaLibraryImageColumn::make('images')
                    ->label('Thumbnail')
                    ->collection('images') // ระบุ collection ให้ตรงกับใน Model/Form
                    ->circular(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('gallery.name')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn (TextColumn $column): string => $column->getState()),

                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
