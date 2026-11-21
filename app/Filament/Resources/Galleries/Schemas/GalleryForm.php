<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->minLength(1)
                    ->maxLength(150)
                    ->placeholder('Enter gallery name')
                    ->required(),

                TextInput::make('slug')
                    ->required()
                    ->unique(),

                RichEditor::make('description')
                    ->columnSpanFull(),

                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),

                FileUpload::make('image')
                    ->disk('public')
                    ->directory('gallery/images')
                    ->visibility('public')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(2048)
                    ->helperText('Max 2MB. Recommended: 1200x630px'),

                Toggle::make('is_active')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->inline(false)
                    ->default(true),
            ]);
    }
}
