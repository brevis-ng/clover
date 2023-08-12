<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = "heroicon-o-tag";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                TextInput::make("name")
                                    ->label(__("category.name"))
                                    ->maxLength(255)
                                    ->required(),
                                FileUpload::make("image")
                                    ->label(__("category.image"))
                                    ->helperText(__("category.image_hint"))
                                    ->image()
                                    ->disk("categories")
                                    ->maxSize(1024)
                                    ->imageResizeMode("cover")
                                    ->imageCropAspectRatio("4:3")
                                    ->imageResizeTargetWidth("640")
                                    ->imageResizeTargetHeight("480")
                                    ->columnSpanFull()
                                    ->getUploadedFileNameForStorageUsing(function (
                                        TemporaryUploadedFile $file
                                    ): string {
                                        return Str::uuid() . "." . $file->getExtension();
                                    }),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(["lg" => 2]),
                Group::make()
                    ->schema([
                        Section::make("Status")->schema([
                            Toggle::make("is_visible")
                                ->label(__("category.visibility"))
                                ->helperText(__("category.visibility_hint"))
                                ->default(true),
                        ]),
                        Section::make(__("category.timeline"))
                            ->schema([
                                Placeholder::make("created_at")
                                    ->label(__("category.created_at"))
                                    ->content(
                                        fn(
                                            Category $record
                                        ): ?string => $record->created_at?->diffForHumans()
                                    ),
                                Placeholder::make("created_at")
                                    ->label(__("category.updated_at"))
                                    ->content(
                                        fn(
                                            Category $record
                                        ): ?string => $record->updated_at?->diffForHumans()
                                    ),
                            ])
                            ->hidden(fn(?Category $record) => $record === null),
                    ])
                    ->columnSpan(["lg" => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("image")
                    ->label(__("category.image"))
                    ->disk("categories")
                    ->defaultImageUrl("/storage/default.jpg")
                    ->square(),
                TextColumn::make("name")
                    ->label(__("category.name"))
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make("is_visible")
                    ->label(__("category.visibility"))
                    ->sortable(),
                TextColumn::make("updated_at")
                    ->label(__("category.updated_at"))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListCategories::route("/"),
            "create" => Pages\CreateCategory::route("/create"),
            "edit" => Pages\EditCategory::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("category.label");
    }
}
