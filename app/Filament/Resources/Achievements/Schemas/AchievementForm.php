<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลรางวัลและความสำเร็จ')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('ชื่อรางวัล / ผลงานความสำเร็จ')
                                    ->placeholder('เช่น รางวัลชนะเลิศ เหรียญทอง การประกวดสิ่งประดิษฐ์คนรุ่นใหม่')
                                    ->maxLength(255)
                                    ->required(),

                                TextInput::make('position')
                                    ->label('ผู้ได้รับรางวัล / สาขาวิชา / หน่วยงาน')
                                    ->placeholder('เช่น นายธนกฤต มณีรัตน์ แผนกวิชาช่างยนต์')
                                    ->maxLength(255),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('facebook_url')
                                    ->label('ลิงก์โพสต์ Facebook หรือลิงก์ข่าว')
                                    ->placeholder('https://www.facebook.com/...')
                                    ->url()
                                    ->maxLength(500),

                                DatePicker::make('published_at')
                                    ->label('วันที่ประกาศ / วันที่ได้รับรางวัล')
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
                            ->label('ข้อความ / รายละเอียดรางวัลและความสำเร็จเพิ่มเติม')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('poster')
                            ->label('รูปภาพโปสเตอร์รางวัล / ความสำเร็จ')
                            ->collection('poster')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->directory('achievements')
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

