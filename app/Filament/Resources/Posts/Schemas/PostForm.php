<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\SeoFields;
use App\PostStatus;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('content')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('resource.post.fields.title'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->minLength(1)
                                            ->maxLength(150)
                                            ->placeholder('Enter post title')
                                            ->required(),

                                        TextInput::make('slug')
                                            ->label(__('resource.post.fields.slug'))
                                            ->required()
                                            ->unique()
                                            ->helperText('URL-friendly version of the title'),

                                        Textarea::make('excerpt')
                                            ->label(__('resource.post.fields.excerpt'))
                                            ->rows(3)
                                            ->placeholder('Brief summary or excerpt')
                                            ->helperText('This will be displayed in post previews'),

                                        RichEditor::make('content')
                                            ->label(__('resource.post.fields.content'))
                                            ->required()
                                            ->placeholder('Write your content here...'),
                                    ]),

                                SeoFields::make(),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Publish')
                                    ->schema([
                                        Select::make('status')
                                            ->label(__('resource.post.fields.status'))
                                            ->required()
                                            ->options(PostStatus::class)
                                            ->default(PostStatus::DRAFT->value)
                                            ->native(false)
                                            ->visible(fn(): bool => auth()->user()->can('Publish:Post')),

                                        DateTimePicker::make('published_at')
                                            ->label(__('resource.post.fields.published_at'))
                                            ->default(now())
                                            ->required()
                                            ->native(false),

                                        Toggle::make('is_featured')
                                            ->label(__('resource.post.fields.is_featured'))
                                            ->default(false)
                                            ->inline(false),

                                        Toggle::make('comment_status')
                                            ->label(__('resource.post.fields.comment_status'))
                                            ->default(true)
                                            ->inline(false),
                                    ])
                                    ->compact(),
                                Section::make('Feature Image')
                                    ->schema([
                                        FileUpload::make('feature_image')
                                            ->multiple()
                                            ->label(__('resource.post.fields.feature_image'))
                                            ->disk('public')
                                            ->directory('post/images')
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
                                    ])
                                    ->collapsible()
                                    ->compact(),

                                // ... หลังจบ Section::make('Feature Image') ...
                                Section::make('Attachments')
                                    ->description('แนบไฟล์เอกสารประกอบบทความ (PDF, Word, Excel, PPT)')
                                    ->schema([
                                        FileUpload::make('attachments')
                                            ->label(__('resource.post.fields.attachments'))
                                            ->multiple() // รองรับหลายไฟล์
                                            ->disk('public')
                                            ->directory('post/attachments')
                                            ->visibility('public')
                                            ->acceptedFileTypes([
                                                'application/pdf',
                                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                                                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
                                            ])
                                            ->preserveFilenames()
                                            ->maxSize(10240) // จำกัด 10MB
                                            ->downloadable()
                                            ->openable()
                                            ->reorderable() // สลับลำดับไฟล์ได้
                                            ->helperText('Support PDF, DOCX, XLSX, PPTX. Max 10MB per file.'),
                                    ])
                                    ->collapsible()
                                    ->compact(),
                                // ... ก่อนเริ่ม Section::make('Organization') ...

                                Section::make('Organization')
                                    ->schema([
                                        CheckboxList::make('categories')
                                            ->label(__('resource.post.fields.categories'))
                                            ->relationship('categories', 'name', fn($query) => $query->active())
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columns(1),

                                        Select::make('parent_id')
                                            ->relationship('parent', 'title')
                                            ->label(__('resource.post.fields.parent_id'))
                                            ->searchable()
                                            ->placeholder('Select parent post')
                                            ->native(false),

                                        SpatieTagsInput::make('tags'),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->compact(),
                            ]),
                    ]),
            ]);
    }
}
