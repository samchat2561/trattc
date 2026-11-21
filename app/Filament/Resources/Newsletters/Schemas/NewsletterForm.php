<?php

namespace App\Filament\Resources\Newsletters\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsletterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลจดหมายข่าวประชาสัมพันธ์')
                    ->description('กรอกรายละเอียดหัวข้อ ฉบับที่ หมวดหมู่ และวันที่เผยแพร่จดหมายข่าว')
                    ->schema([
                        TextInput::make('title')
                            ->label('หัวข้อจดหมายข่าวประชาสัมพันธ์')
                            ->placeholder('เช่น จดหมายข่าว วท.ตราด ฉบับที่ 24 ประจำเดือนพฤศจิกายน 2569')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('issue_no')
                                    ->label('ฉบับที่ / ปีที่')
                                    ->placeholder('เช่น ฉบับที่ 24/2569')
                                    ->maxLength(100),

                                Select::make('category')
                                    ->label('หมวดหมู่จดหมายข่าว')
                                    ->options([
                                        'ข่าวกิจกรรมสถานศึกษา' => 'ข่าวกิจกรรมสถานศึกษา',
                                        'ข่าววิชาการและทักษะวิชาชีพ' => 'ข่าววิชาการและทักษะวิชาชีพ',
                                        'ข่าวพัฒนานักเรียน นักศึกษา' => 'ข่าวพัฒนานักเรียน นักศึกษา',
                                        'ข่าวบริการวิชาชีพสู่ชุมชน' => 'ข่าวบริการวิชาชีพสู่ชุมชน',
                                        'ข่าวสารทั่วไป' => 'ข่าวสารทั่วไป',
                                    ])
                                    ->searchable()
                                    ->default('ข่าวกิจกรรมสถานศึกษา'),

                                DatePicker::make('published_at')
                                    ->label('วันที่เผยแพร่')
                                    ->default(now())
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('uploader_name')
                                    ->label('ชื่อผู้อัปโหลดข้อมูล')
                                    ->default(fn () => auth()->user()?->name ?? 'งานประชาสัมพันธ์')
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
                            ->label('เนื้อหา / สรุปรายละเอียดจดหมายข่าว')
                            ->placeholder('ระบุเนื้อหาข่าวสาร ความเป็นมา หรือรายละเอียดกิจกรรมโดยสังเขป')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('รูปภาพหน้าปกจดหมายข่าว')
                    ->description('อัปโหลดรูปภาพหน้าปก จดหมายข่าวฉบับแรก หรือภาพไฮไลต์ (ถ้าไม่มีจะใช้ภาพมาตรฐาน)')
                    ->schema([
                        FileUpload::make('image')
                            ->label('รูปภาพหน้าปก')
                            ->disk('public')
                            ->directory('newsletters/images')
                            ->visibility('public')
                            ->image()
                            ->maxSize(10240)
                            ->helperText('รองรับ JPG, PNG, WebP ขนาดไม่เกิน 10MB')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),

                Section::make('ไฟล์เอกสารแนบฉบับเต็ม (PDF, Word, Excel, PowerPoint, ZIP)')
                    ->description('อัปโหลดไฟล์จดหมายข่าวฉบับเต็ม เอกสารประกอบ หรือไฟล์นำเสนอเพื่อเปิดให้ดาวน์โหลด')
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('ไฟล์เอกสารดาวน์โหลด')
                            ->multiple()
                            ->disk('public')
                            ->directory('newsletters/attachments')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/msword', // .doc
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                                'application/vnd.ms-excel', // .xls
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
                                'application/vnd.ms-powerpoint', // .ppt
                                'application/zip', // .zip
                                'application/x-zip-compressed',
                                'application/octet-stream',
                            ])
                            ->preserveFilenames()
                            ->maxSize(51200) // 50MB
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->helperText('รองรับไฟล์ PDF, DOCX, XLSX, PPTX, ZIP (ขนาดสูงสุด 50MB ต่อไฟล์ สามารถเลือกพร้อมกันได้หลายไฟล์)')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
