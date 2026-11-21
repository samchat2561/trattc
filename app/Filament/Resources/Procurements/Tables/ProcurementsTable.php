<?php

namespace App\Filament\Resources\Procurements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProcurementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('รูปภาพ')
                    ->disk('public')
                    ->defaultImageUrl(asset('images/procurements/procurement_default.svg'))
                    ->square(),

                TextColumn::make('title')
                    ->label('ชื่องานประกาศ')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title),

                TextColumn::make('procurement_type')
                    ->label('ประเภทประกาศ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)' => 'primary',
                        'ประกาศราคากลาง' => 'warning',
                        'ประกาศผู้ชนะการเสนอราคา' => 'info',
                        'เผยแพร่แผนการจัดซื้อจัดจ้าง' => 'gray',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('budget')
                    ->label('งบประมาณ / ราคากลาง')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('published_at')
                    ->label('วันที่ประกาศ')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('deadline_at')
                    ->label('สิ้นสุดรับข้อเสนอ')
                    ->date('d/m/Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('สถานะ')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'closed' => 'สิ้นสุดรับข้อเสนอ',
                        'awarded' => 'ประกาศผลผู้ชนะ',
                        default => 'เปิดรับข้อเสนอ',
                    })
                    ->color(fn ($state) => match ($state) {
                        'closed' => 'gray',
                        'awarded' => 'info',
                        default => 'success',
                    }),

                TextColumn::make('attachments')
                    ->label('ไฟล์แนบ')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' ไฟล์' : '0 ไฟล์')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('link_url')
                    ->label('คลิกลิงก์')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => $state ? 'เปิดลิงก์' : '-')
                    ->url(fn ($record) => $record->link_url, shouldOpenInNewTab: true)
                    ->toggleable(isToggledHiddenByDefault: false),

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
