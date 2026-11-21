<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use App\Models\Department;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Builder;

use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // ส่วนที่ 1: ข้อมูลโครงสร้างหลักของหน่วยงาน
                        Section::make('ข้อมูลโครงสร้างหน่วยงาน')
                            ->description('กำหนดชื่อ รหัส และหน่วยงานต้นสังกัดหลัก')
                            ->schema([
                                Select::make('parent_id')
                                    ->label('สังกัดหน่วยงานหลัก')
                                    ->helperText('หากเป็นหน่วยงานระดับสูงสุดของสถาบัน (เช่น ฝ่ายบริหาร) ให้เว้นว่างไว้')
                                    ->relationship(
                                        name: 'parent',
                                        titleAttribute: 'name',
                                        // ป้องกันไม่ให้หน่วยงานกดเลือกตัวเองเป็นหน่วยงานหลัก (ป้องกัน Loop)
                                        modifyQueryUsing: fn(Builder $query, ?Department $record) =>
                                        $record ? $query->where('id', '!=', $record->id) : $query
                                    )
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('name')
                                    ->label('ชื่อหน่วยงาน / ฝ่าย / คณะ')
                                    ->placeholder('เช่น คณะเทคโนโลยีสารสนเทศ, งานทะเบียน')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('code')
                                    ->label('รหัสหน่วยงาน')
                                    ->placeholder('เช่น IT-01, REG-02')
                                    ->maxLength(50),
                            ])
                            ->columnSpan(2),

                        // ส่วนที่ 2: การควบคุมสถานะและการจัดเรียงในผัง
                        Section::make('การควบคุมและการแสดงผล')
                            ->description('ตั้งค่าลำดับที่จะแสดงในแผนผังโครงสร้าง')
                            ->schema([
                                Toggle::make('status')
                                    ->label('สถานะการใช้งานหน่วยงาน')
                                    ->helperText('หากปิดใช้งาน บุคลากรในหน่วยงานนี้จะไม่แสดงผลบนหน้าเว็บ')
                                    ->default(true)
                                    ->required(),

                                TextInput::make('sort_order')
                                    ->label('ลำดับการแสดงผล')
                                    ->helperText('ระบุเป็นตัวเลข (เลขน้อยจะถูกจัดให้อยู่ฝั่งซ้ายหรือด้านบนก่อน)')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columnSpan(1),
                    ]),

                // ส่วนที่ 3: รายละเอียดเพิ่มเติม
                Section::make('ข้อมูลเพิ่มเติม')
                    ->schema([
                        Textarea::make('description')
                            ->label('รายละเอียดภาระงาน / คำอธิบายหน่วยงานย่อ')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
