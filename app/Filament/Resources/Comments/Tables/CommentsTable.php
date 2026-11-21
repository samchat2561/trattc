<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Filament\Resources\Comments\Trait\ReplyAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsTable
{
    use ReplyAction;

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('post.title')
                    ->label('บทความ')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('ผู้เขียน (สมาชิก)')
                    ->default('Guest')
                    ->sortable()
                    ->searchable(),
                    
                // แสดงเนื้อหาคอมเมนต์เดิม (เปลี่ยนจาก parent.body เป็น body ของตัวเองเพื่อให้ดูง่าย)
                TextColumn::make('body')
                    ->label('ข้อความ')
                    ->limit(50)
                    ->searchable(),

                // --- เพิ่มคอลัมน์ ถูกใจ ตรงนี้ ---
                TextColumn::make('likes_count')
                    ->counts('likes')
                    ->label('ถูกใจ')
                    ->badge()
                    ->icon('heroicon-s-heart')
                    ->color('danger')
                    ->sortable(),
                // ---------------------------

                TextColumn::make('guest_name')
                    ->label('Guest name')
                    ->searchable(),

                TextColumn::make('guest_email')
                    ->label('Guest email')
                    ->searchable(),

                IconColumn::make('is_approved')
                    ->label('อนุมัติ')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('วันที่เขียน')
                    ->dateTime('d/m/Y H:i')
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
                EditAction::make(),
                self::getReplyAction(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
