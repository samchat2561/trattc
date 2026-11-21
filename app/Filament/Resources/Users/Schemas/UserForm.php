<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor\TextColor;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),

                TextInput::make('password')
                    ->password()
                    ->required(),

                TextInput::make('profile_photo'),

                TextInput::make('tagline'),

                RichEditor::make('bio')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript','grid', 'textColor', 'link'],
                        ['h2', 'h3'],
                        ['alignStart', 'alignCenter', 'alignEnd'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                        ['undo', 'redo'],
                    ])
                    ->floatingToolbars([
                        'paragraph' => [
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'subscript',
                            'superscript',
                        ],
                        'heading' => [
                            'h1',
                            'h2',
                            'h3',
                        ],
                        'table' => [
                            'tableAddColumnBefore',
                            'tableAddColumnAfter',
                            'tableDeleteColumn',
                            'tableAddRowBefore',
                            'tableAddRowAfter',
                            'tableDeleteRow',
                            'tableMergeCells',
                            'tableSplitCell',
                            'tableToggleHeaderRow',
                            'tableToggleHeaderCell',
                            'tableDelete',
                        ],
                    ])
                    ->columnSpanFull(),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }
}
