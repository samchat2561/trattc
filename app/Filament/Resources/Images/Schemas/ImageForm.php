<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set; // อย่าลืม import class นี้
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\RichEditor;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->minLength(1)
                    ->maxLength(150)
                    ->placeholder('Enter image title')
                    ->required(),

                TextInput::make('slug')
                    ->required()
                    ->unique(),

                Textarea::make('excerpt')
                    ->rows(3)
                    ->placeholder('Brief summary or excerpt')
                    ->helperText('This will be displayed in post previews')
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->required()
                    ->placeholder('Write your content here...')
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('image')
                    ->multiple()
                    ->collection('images') // ชื่อ collection ที่ตั้งไว้ใน Model
                    ->disk('public') // บันทึกลง public disk เพื่อให้เข้าถึงผ่าน URL ได้
                    ->responsiveImages()    // ตัวเลือกเสริม
                    ->image() // ตรวจสอบว่าเป็นไฟล์รูปภาพ
                    ->imageEditor(),

                Toggle::make('is_published')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->inline(false)
                    ->default(true),

                DateTimePicker::make('published_at')
                    ->default(now())
                    ->required(),

                Select::make('gallery_id')
                    ->relationship('gallery', 'name')
                    ->required(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('view_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
