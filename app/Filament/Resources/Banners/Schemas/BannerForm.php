<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลพื้นฐาน')
                    ->schema([
                        // ส่วนที่ 1: ชื่อแบนเนอร์ และประเภทแบนเนอร์ (แบ่งเป็น 2 คอลัมน์)
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('หัวข้อ')
                                    ->placeholder('Enter title')
                                    ->maxLength(255)
                                    ->required(),

                                Select::make('banner_type')
                                    ->label('ประเภท')
                                    ->options([
                                        'slider' => 'Slider (Homepage Top)',
                                        'grid' => 'Grid (Promo Section)',
                                    ])
                                    ->default('slider')
                                    ->required(),
                            ]),

                        // ส่วนที่ 2: ข้อความปุ่ม และ ลิงก์ปลายทาง (แบ่งเป็น 2 คอลัมน์)
                        Grid::make(2)
                            ->schema([
                                TextInput::make('button_text')
                                    ->label('ข้อความปุ่ม')
                                    ->placeholder('Enter button text')
                                    ->maxLength(255),

                                TextInput::make('button_url')
                                    ->label('ลิงก์ปลายทาง')
                                    ->placeholder('https://example.com')
                                    ->url()
                                    ->maxLength(255),
                            ]),

                        // ส่วนที่ 3: ลำดับการแสดงผล และ สถานะเปิด/ปิด (แบ่งเป็น 2 คอลัมน์)
                        Grid::make(2)
                            ->schema([
                                TextInput::make('position')
                                    ->label('ลำดับ')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Toggle::make('is_active')
                                    ->label('สถานะ')
                                    ->onIcon('heroicon-m-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->inline(false)
                                    ->default(true),
                            ]),

                        // ส่วนที่ 4: ตั้งเวลาเริ่มต้น และ สิ้นสุดการแสดงผล (แบ่งเป็น 2 คอลัมน์)
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('starts_at')
                                    ->label('เริ่มต้นที่'),

                                DateTimePicker::make('ends_at')
                                    ->label('สิ้นสุดที่'),
                            ]),

                        // ส่วนที่ 5: คำอธิบายเพิ่มเติม (เต็มความกว้าง)
                        Textarea::make('subtitle')
                            ->label('คำอธิบายเพิ่มเติม')
                            ->rows(3)
                            ->maxLength(65535),

                        // ส่วนที่ 6: อัปโหลดรูปภาพแบนเนอร์ (เต็มความกว้าง)
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('รูปภาพแบนเนอร์')
                            ->collection('banner_image')
                            ->disk('public') // บันทึกลง public disk เพื่อให้เข้าถึงผ่าน URL ได้
                            ->image()
                            ->imageEditor()
                            ->directory('banners')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(), // ใช้แบบพิมพ์เล็กตามมาตรฐานโครงสร้างคอมโพเนนต์
            ]);
    }
}
