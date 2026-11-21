<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลบทความและผู้เขียน')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('post_id')
                                    ->relationship('post', 'title')
                                    ->label('บทความ')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('ผู้ใช้งาน (ถ้ามี)')
                                    ->searchable()
                                    ->placeholder('เลือกผู้ใช้งานในระบบ')
                                    ->nullable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('guest_name')
                                    ->label('ชื่อผู้เยี่ยมชม(Guest)')
                                    ->placeholder('กรณีไม่ได้ Login'),

                                TextInput::make('guest_email')
                                    ->label('อีเมลผู้เยี่ยมชม  (Guest)')
                                    ->email(),
                            ]),
                    ]),

                Section::make('เนื้อหาความคิดเห็น')
                    ->schema([
                        Select::make('parent_id')
                            ->relationship('parent', 'id') // หรือเลือก field ที่ระบุตัวตนคอมเมนต์แม่
                            ->label('ตอบกลับความคิดเห็นที่')
                            ->placeholder('ปล่อยว่างถ้าเป็นคอมเมนต์หลัก')
                            ->searchable()
                            ->nullable(),

                        Textarea::make('body')
                            ->label('ข้อความ')
                            ->placeholder('เขียนความคิดเห็นที่นี่...')
                            ->required()
                            ->rows(5)
                            ->autosize() // ปรับความสูงตามเนื้อหาอัตโนมัติ
                            ->columnSpanFull(),

                        Toggle::make('is_approved')
                            ->label('อนุมัติการแสดงผล')
                            ->default(false)
                            ->required(),
                    ]),
            ]);
    }

    public static function getReplyForm(): array
    {
        return [
            Textarea::make('body')
                ->label('ข้อความตอบกลับ')
                ->placeholder('พิมพ์ข้อความตอบกลับที่นี่...')
                ->required()
                ->rows(3)
                ->autosize()
                ->columnSpanFull(),

            Toggle::make('is_approved')
                ->label('อนุมัติการแสดงผลทันที')
                ->default(fn () => session('admin_comment_approve_pref', true)) // ดึงจาก session ถ้าไม่มีให้เป็น true
                ->live() // อัปเดตทันทีเมื่อมีการคลิก
                ->afterStateUpdated(function ($state) {
                    // เมื่อ Admin เปลี่ยนค่า ให้บันทึกลง session ทันที
                    session(['admin_comment_approve_pref' => $state]);
                })
                ->onColor('success')
                ->offColor('gray')
                ->helperText('ระบบจะจดจำตัวเลือกนี้ไว้ในการตอบกลับครั้งถัดไป'),
        ];
    }
}
