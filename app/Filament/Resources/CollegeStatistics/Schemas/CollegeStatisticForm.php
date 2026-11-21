<?php

namespace App\Filament\Resources\CollegeStatistics\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CollegeStatisticForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลหัวข้อสถิติสถานศึกษา')
                    ->description('กำหนดข้อมูลพื้นฐานของสถิติและประเภทกราฟที่ต้องการนำเสนอ')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('ชื่อหัวข้อสถิติ')
                                    ->placeholder('เช่น นักเรียน-นักศึกษาทั้งหมด')
                                    ->maxLength(255)
                                    ->required(),

                                TextInput::make('code')
                                    ->label('รหัสอ้างอิง (Slug/Code)')
                                    ->placeholder('เช่น students_total, staff_by_gender')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100)
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('subtitle')
                                    ->label('คำอธิบายสถิติเสริม')
                                    ->placeholder('เช่น ประจำปีการศึกษา 2567')
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                TextInput::make('unit')
                                    ->label('หน่วยนับ')
                                    ->default('คน')
                                    ->placeholder('คน, แผนก, งาน')
                                    ->maxLength(50)
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('chart_type')
                                    ->label('รูปแบบกราฟ (Chart Type)')
                                    ->options([
                                        'doughnut' => '🍩 โดนัท (Doughnut Chart)',
                                        'pie' => '🥧 พาย (Pie Chart)',
                                        'bar' => '📊 แท่งแนวตั้ง (Bar Chart)',
                                        'horizontal-bar' => '📊 แท่งแนวนอน (Horizontal Bar)',
                                        'polarArea' => '🎯 วงกลมโพลาร์ (Polar Area)',
                                    ])
                                    ->default('doughnut')
                                    ->required(),

                                Select::make('color')
                                    ->label('โทนสีการ์ด / ธีม')
                                    ->options([
                                        'maroon' => '🔴 เลือดหมู (Maroon / วิทยาลัยเทคนิคตราด)',
                                        'blue' => '🔵 ฟ้า / น้ำเงิน (Blue)',
                                        'rose' => '🌸 ชมพู (Rose)',
                                        'amber' => '🟠 ส้ม / ทอง (Amber)',
                                        'purple' => '🟣 ม่วง (Purple)',
                                        'emerald' => '🟢 เขียวมรกต (Emerald)',
                                        'indigo' => '🔷 คราม (Indigo)',
                                        'teal' => '💠 เขียวน้ำทะเล (Teal)',
                                    ])
                                    ->default('maroon')
                                    ->required(),

                                TextInput::make('icon')
                                    ->label('ไอคอน FontAwesome')
                                    ->placeholder('เช่น fas fa-user-graduate')
                                    ->default('fas fa-chart-pie')
                                    ->maxLength(100),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('total_count')
                                    ->label('ยอดรวมทั้งหมด (ใส่ 0 เพื่อให้ระบบรวมอัตโนมัติ)')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('หากปล่อย 0 ระบบจะรวมตัวเลขจากรายการแจกแจงด้านล่างให้อัตโนมัติ'),

                                TextInput::make('sort_order')
                                    ->label('ลำดับการแสดงผล')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('เปิดแสดงผลบนหน้าแรก')
                                    ->onIcon('heroicon-m-check')
                                    ->offIcon('heroicon-m-x-mark')
                                    ->default(true),
                            ]),

                        Textarea::make('description')
                            ->label('คำอธิบายรายละเอียดเพิ่มเติม')
                            ->placeholder('ข้อมูลสถิติตามระบบ PMS วิทยาลัยเทคนิคตราด...')
                            ->rows(2)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Section::make('รายการข้อมูลแจกแจงสถิติ (Data Breakdown & Chart Values)')
                    ->description('เพิ่มและแก้ไขตัวเลขข้อมูลย่อยที่จะนำไปวาดกราฟและตารางแจกแจง')
                    ->schema([
                        Repeater::make('items')
                            ->label('รายการข้อมูลย่อยในกราฟ')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextInput::make('label')
                                            ->label('ชื่อรายการ / หมวดหมู่')
                                            ->placeholder('เช่น หญิง, ชาย, ช่างยนต์')
                                            ->required()
                                            ->columnSpan(2),

                                        TextInput::make('value')
                                            ->label('จำนวนตัวเลข')
                                            ->numeric()
                                            ->minValue(0)
                                            ->required()
                                            ->columnSpan(1),

                                        ColorPicker::make('color')
                                            ->label('สีของกราฟ')
                                            ->default('#3b82f6')
                                            ->columnSpan(1),
                                    ]),

                                TextInput::make('note')
                                    ->label('หมายเหตุ / รายละเอียดเพิ่มเติม')
                                    ->placeholder('เช่น คิดเป็น 45%, ระดับ ปวช. และ ปวส.')
                                    ->maxLength(255),
                            ])
                            ->collapsible()
                            ->collapsed(false)
                            ->itemLabel(fn (array $state): ?string => ($state['label'] ?? null) ? ($state['label'] . ' (' . number_format($state['value'] ?? 0) . ')') : null)
                            ->addActionLabel('เพิ่มรายการข้อมูล')
                            ->reorderable()
                            ->cloneable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
