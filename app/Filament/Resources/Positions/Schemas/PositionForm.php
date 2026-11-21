<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // ส่วนที่ 1: ข้อมูลตำแหน่ง
                        Section::make('ข้อมูลตำแหน่งบริหาร')
                            ->description('กำหนดชื่อตำแหน่งยศ และระดับความสูงในสายบังคับบัญชา')
                            ->schema([
                                TextInput::make('name')
                                    ->label('ชื่อตำแหน่งบริหาร')
                                    ->placeholder('เช่น อธิการบดี, รองอธิการบดี, คณบดี')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('level')
                                    ->label('ระดับตำแหน่ง (Level)')
                                    ->helperText('ยิ่งตัวเลขอันดับน้อย ตำแหน่งยิ่งสูง (เช่น 1 = สูงสุดขององค์กร)')
                                    ->required()
                                    ->numeric()
                                    ->default(1),
                            ])
                            ->columnSpan(2),

                        // ส่วนที่ 2: รูปแบบการแสดงผลบนผังองค์กร
                        Section::make('การจัดเรียงและสีแท็ก')
                            ->description('ตั้งค่าสีของกล่องข้อความและลำดับบนหน้าผังเว็บไซต์')
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('ลำดับการแสดงผล')
                                    ->helperText('ใช้จัดเรียงลำดับซ้าย-ขวา ในกรณีที่อยู่ Level เดียวกัน')
                                    ->required()
                                    ->numeric()
                                    ->default(0),

                                ColorPicker::make('color')
                                    ->label('สีสัญลักษณ์ประจำตำแหน่ง')
                                    ->helperText('เลือกเฉดสีที่จะไปแสดงเป็นเส้นขอบหรือพื้นหลังของแท็กตำแหน่ง')
                                    ->default('#1e40af'), // Default: Blue-800
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
