<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // ส่วนที่ 1: รูปโปรไฟล์และข้อมูลส่วนตัวพื้นฐาน
                        Section::make('ข้อมูลส่วนตัวบุคลากร')
                            ->description('จัดการรูปถ่าย คำนำหน้า ชื่อ และนามสกุล')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('profile')
                                    ->label('รูปถ่ายโปรไฟล์')
                                    ->collection('profile')
                                    ->disk('public') // บันทึกลง public disk เพื่อให้เข้าถึงผ่าน URL ได้
                                    ->directory('images/staff')
                                    ->visibility('public')
                                    ->avatar()
                                    ->imageEditor()
                                    // ->circleCropper()
                                    ->columnSpanFull(),

                                TextInput::make('prefix')
                                    ->label('คำนำหน้าชื่อ')
                                    ->placeholder('เช่น นาย, นางสาว, ดร., ผศ.')
                                    ->maxLength(50),

                                TextInput::make('first_name')
                                    ->label('ชื่อจริง')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('last_name')
                                    ->label('นามสกุล')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columnSpan(2),

                        // ส่วนที่ 2: สถานะและการจัดเรียง
                        Section::make('สถานะการทำงาน')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('เปิดใช้งานสถานะบุคลากร')
                                    ->helperText('หากปิดใช้งาน จะไม่แสดงผลในผังองค์กรหน้าเว็บ')
                                    ->default(true)
                                    ->required(),

                                TextInput::make('sort_order')
                                    ->label('ลำดับการแสดงผล')
                                    ->helperText('ตัวเลขน้อยจะขึ้นก่อนในระดับเดียวกัน')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columnSpan(1),
                    ]),

                // ส่วนที่ 3: โครงสร้างและการบังคับบัญชา
                Section::make('ตำแหน่งและสายงานบังคับบัญชา')
                    ->description('กำหนดหน่วยงาน ตำแหน่งบริหาร และผู้บังคับบัญชาโดยตรง')
                    ->schema([
                        Select::make('department_id')
                            ->label('ฝ่าย / สำนัก / คณะ / งาน')
                            ->relationship(
                                name: 'department',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('sort_order')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('position_id')
                            ->label('ตำแหน่งงาน / ตำแหน่งบริหาร')
                            ->relationship(
                                name: 'position',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('sort_order')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('manager_id')
                            ->label('ผู้บังคับบัญชาโดยตรง')
                            ->relationship(
                                name: 'manager',
                                modifyQueryUsing: fn(Builder $query) => $query->where('is_active', true)
                            )
                            // ค้นหาและแสดงผลด้วย ชื่อ - นามสกุล แทนการใช้ ID
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->prefix}{$record->first_name} {$record->last_name}")
                            ->searchable(['first_name', 'last_name'])
                            ->preload(),
                    ])
                    ->columns(3),

                // ส่วนที่ 4: ข้อมูลการติดต่อและประวัติ
                Section::make('ข้อมูลติดต่อและประวัติย่อ')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('email')
                                    ->label('อีเมลติดต่อ')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('เบอร์โทรศัพท์ภายใน/มือถือ')
                                    ->tel()
                                    ->maxLength(50),

                                TextInput::make('office')
                                    ->label('ห้องปฏิบัติงาน / อาคาร')
                                    ->placeholder('เช่น อาคาร 3 ชั้น 2 ห้อง 321')
                                    ->maxLength(255),
                            ]),

                        Textarea::make('biography')
                            ->label('ประวัติการศึกษาและการทำงานย่อ')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
