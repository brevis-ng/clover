<?php

namespace App\Filament\Resources;

use App\Enums\Units;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = "heroicon-o-lightning-bolt";
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                TextInput::make("name")
                                    ->label(__("product.name"))
                                    ->required(),
                                TextInput::make("code")
                                    ->label(__("product.code"))
                                    ->hint(__("product.code_hint"))
                                    ->required(),
                                TextInput::make("remarks")
                                    ->label(__("product.remarks"))
                                    ->helperText(__("product.remarks_hint"))
                                    ->maxLength(30),
                                Select::make("unit")
                                    ->label(__("product.unit"))
                                    ->options(Units::all())
                                    ->default(Units::NONE),
                                MarkdownEditor::make("description")
                                    ->label(__("product.description"))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Section::make(__("product.sec_image"))
                            ->schema([
                                FileUpload::make("image")
                                    ->label(__("product.image"))
                                    ->helperText(__("product.image_hint"))
                                    ->image()
                                    ->disk("products")
                                    ->maxSize(1024)
                                    ->imageResizeMode("cover")
                                    ->imageCropAspectRatio("4:3")
                                    ->imageResizeTargetWidth("640")
                                    ->imageResizeTargetHeight("480")
                                    ->getUploadedFileNameForStorageUsing(function (
                                        TemporaryUploadedFile $file
                                    ): string {
                                        return Str::uuid() . "." . $file->guessExtension();
                                    })
                                    ->enableDownload(),
                            ])
                            ->collapsible(),
                        Section::make(__("product.sec_price"))
                            ->schema([
                                TextInput::make("price")
                                    ->label(__("product.price"))
                                    ->hint(__("product.price_hint"))
                                    ->numeric()
                                    ->required()
                                    ->default(0),
                                TextInput::make("old_price")
                                    ->label(__("product.old_price"))
                                    ->hint(__("product.old_price_hint"))
                                    ->numeric(),
                                TextInput::make("cost")
                                    ->label(__("product.cost"))
                                    ->helperText(__("product.cost_hint"))
                                    ->numeric(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(["lg" => 2]),
                Group::make()
                    ->schema([
                        Section::make("Status")->schema([
                            Toggle::make("is_visible")
                                ->label(__("product.visibility"))
                                ->helperText(__("product.visibility_hint"))
                                ->default(true),
                        ]),
                        Section::make(__("product.associations"))->schema([
                            Select::make("category_id")
                                ->label(__("product.category"))
                                ->options(Category::all()->pluck("name", "id"))
                                ->searchable()
                                ->required(),
                        ]),
                        Section::make(__("product.variation"))->schema([
                            Select::make("variations")
                                ->label(__("product.variation"))
                                ->relationship("variations", "name")
                                ->multiple()
                                ->searchable(),
                        ]),
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
                    ->label(__("product.image"))
                    ->disk("products")
                    ->defaultImageUrl("/storage/default.jpg")
                    ->square(),
                TextColumn::make("name")
                    ->label(__("product.name"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("code")
                    ->label(__("product.code"))
                    ->sortable()
                    ->searchable(),
                TextColumn::make("category.name")
                    ->label(__("product.category"))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("price")
                    ->label(__("product.price"))
                    ->money(shouldConvert: true)
                    ->sortable(),
                TextColumn::make("old_price")
                    ->label(__("product.old_price"))
                    ->money(shouldConvert: true)
                    ->sortable()
                    ->toggleable(),
                SelectColumn::make("unit")
                    ->label(__("product.unit"))
                    ->options(Units::all())
                    ->disablePlaceholderSelection()
                    ->toggleable(),
                ToggleColumn::make("is_visible")
                    ->label(__("product.visibility"))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("updated_at")
                    ->label(__("product.updated_at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort("updated_at", "desc")
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
            "index" => Pages\ListProducts::route("/"),
            "create" => Pages\CreateProduct::route("/create"),
            "edit" => Pages\EditProduct::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("product.label");
    }
}
