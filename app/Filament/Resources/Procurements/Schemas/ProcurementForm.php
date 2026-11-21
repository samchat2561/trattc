<?php

namespace App\Filament\Resources\Procurements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProcurementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ข้อมูลประกาศจัดซื้อ-จัดจ้าง')
                    ->description('กรอกรายละเอียดข้อมูลการจัดซื้อจัดจ้าง และกำหนดเวลาการรับข้อเสนอ')
                    ->schema([
                        TextInput::make('title')
                            ->label('ชื่องาน / หัวข้อประกาศจัดซื้อจัดจ้าง')
                            ->placeholder('เช่น ประกวดราคาซื้อครุภัณฑ์การศึกษา แผนกวิชาช่างอิเล็กทรอนิกส์ ด้วยวิธี e-Bidding')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Select::make('procurement_type')
                                    ->label('ประเภทประกาศ')
                                    ->options([
                                        'ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)' => 'ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)',
                                        'ประกาศราคากลาง' => 'ประกาศราคากลาง',
                                        'ประกาศผู้ชนะการเสนอราคา' => 'ประกาศผู้ชนะการเสนอราคา',
                                        'เผยแพร่แผนการจัดซื้อจัดจ้าง' => 'เผยแพร่แผนการจัดซื้อจัดจ้าง',
                                        'สอบราคา' => 'สอบราคา',
                                        'เฉพาะเจาะจง / ตกลงราคา' => 'เฉพาะเจาะจง / ตกลงราคา',
                                        'ร่างเอกสารประกวดราคา (TOR)' => 'ร่างเอกสารประกวดราคา (TOR)',
                                    ])
                                    ->searchable()
                                    ->placeholder('เลือกประเภทประกาศ')
                                    ->default('ประกวดราคาอิเล็กทรอนิกส์ (e-Bidding)'),

                                TextInput::make('budget')
                                    ->label('วงเงินงบประมาณ / ราคากลาง')
                                    ->placeholder('เช่น 1,250,000 บาท')
                                    ->maxLength(100),

                                Select::make('status')
                                    ->label('สถานะประกาศ')
                                    ->options([
                                        'active' => 'กำลังเปิดรับข้อเสนอ / เผยแพร่',
                                        'closed' => 'สิ้นสุดรับข้อเสนอแล้ว',
                                        'awarded' => 'ประกาศผลผู้ชนะแล้ว',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('published_at')
                                    ->label('วันที่ประกาศ')
                                    ->default(now())
                                    ->required(),

                                DatePicker::make('deadline_at')
                                    ->label('วันสิ้นสุดรับข้อเสนอ / ยื่นเอกสาร')
                                    ->helperText('เว้นว่างได้หากไม่มีกำหนดเวลาสิ้นสุด'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('uploader_name')
                                    ->label('ชื่อผู้อัปโหลดประกาศ')
                                    ->default(fn () => auth()->user()?->name ?? 'งานพัสดุ')
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

                        TextInput::make('link_url')
                            ->label('คลิกลิงก์ดาวน์โหลด / ลิงก์เอกสารภายนอก (เช่น Google Drive หรือ URL ประกาศ)')
                            ->placeholder('https://drive.google.com/... หรือ https://trattc.ac.th/...')
                            ->maxLength(500)
                            ->url()
                            ->helperText('ลิงก์ดาวน์โหลดเอกสาร เช่น Google Drive หรือ ลิงก์รายละเอียดเพิ่มเติมตามตัวอย่างใน trattc.ac.th/post3')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('รายละเอียดประกาศจัดซื้อจัดจ้างเพิ่มเติม')
                            ->placeholder('ระบุรายละเอียด เงื่อนไข หรือข้อความประกาศเพิ่มเติม')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('รูปภาพประกาศ / ปกเอกสาร')
                    ->description('อัปโหลดรูปภาพโปสเตอร์หรือภาพปกของประกาศจัดซื้อจัดจ้าง (ถ้าไม่มีจะใช้ภาพมาตรฐาน)')
                    ->schema([
                        FileUpload::make('image')
                            ->label('รูปภาพปกประกาศ')
                            ->disk('public')
                            ->directory('procurements/images')
                            ->visibility('public')
                            ->image()
                            ->maxSize(10240)
                            ->helperText('รองรับ JPG, PNG, WebP ขนาดไม่เกิน 10MB')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),

                Section::make('ไฟล์เอกสารแนบ (PDF, Word, Excel, PowerPoint, ZIP)')
                    ->description('อัปโหลดไฟล์เอกสารแนบ เช่น TOR, ราคากลาง, แบบรูปรายการ, ใบเสนอราคา หรือเอกสารประกวดราคา')
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('เอกสารแนบดาวน์โหลด')
                            ->multiple()
                            ->disk('public')
                            ->directory('procurements/attachments')
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
