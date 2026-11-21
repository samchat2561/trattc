<?php

namespace App\Filament\Resources\TeacherCongratulations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherCongratulationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลการแสดงความยินดี')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('ชื่อ-นามสกุล / หัวข้อ')
                                    ->placeholder('เช่น นางสาวสุภาพร สายจันทร์')
                                    ->maxLength(255)
                                    ->required(),

                                TextInput::make('position')
                                    ->label('ตำแหน่ง / สังกัด / สาขาวิชา')
                                    ->placeholder('เช่น ครูผู้ช่วย แผนกวิชาการโรงแรม')
                                    ->maxLength(255),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('ลิงก์โพสต์ Facebook')
                                    ->placeholder('https://www.facebook.com/...')
                                    ->url()
                                    ->maxLength(500),

                                DatePicker::make('published_at')
                                    ->label('วันที่ประกาศ')
                                    ->default(now())
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('uploader_name')
                                    ->label('ชื่อผู้อัพโหลดข้อมูล')
                                    ->placeholder('เช่น นายศิริรักษ์')
                                    ->default(fn () => auth()->user()?->name ?? 'แอดมิน')
                                    ->maxLength(255),

                                TextInput::make('views_count')
                                    ->label('จำนวนการเข้าชม')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                TextInput::make('sort_order')
                                    ->label('ลำดับการแสดงผล')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Toggle::make('is_active')
                            ->label('เปิดแสดงผลบนหน้าแรก')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->default(true),

                        Textarea::make('description')
                            ->label('ข้อความ / รายละเอียดการแสดงความยินดีเพิ่มเติม')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('poster')
                            ->label('รูปภาพโปสเตอร์แสดงความยินดี')
                            ->collection('poster')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('congratulations')
                            ->required()
                            ->columnSpanFull()
                            ->getUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, string $file): ?array {
                                if (! $component->getRecord()) {
                                    return null;
                                }

                                /** @var ?\Spatie\MediaLibrary\MediaCollections\Models\Media $media */
                                $media = $component->getRecord()->getRelationValue('media')->firstWhere('uuid', $file);

                                if (! $media) {
                                    return null;
                                }

                                return [
                                    'name' => $media->file_name,
                                    'size' => $media->size,
                                    'type' => $media->mime_type,
                                    'url' => '/storage/' . $media->id . '/' . $media->file_name,
                                ];
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
