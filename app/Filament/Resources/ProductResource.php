<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tên sản phẩm')
                    ->placeholder('Nhập vào tên sản phẩm')
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label('Mã sản phẩm')
                    ->placeholder('Nhập mã của sản phẩm, không được trùng nhau')
                    ->maxLength(5)
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->label('Giá')
                    ->suffix('PHP')
                    ->minValue(0),
                Forms\Components\FileUpload::make('image')
                    ->label('Hình ảnh')
                    ->image(),
                Forms\Components\Radio::make('status')
                    ->label('Trạng thái')
                    ->inline()
                    ->options(ProductStatus::all())
                    ->default(ProductStatus::INSTOCK->value),
                Forms\Components\RichEditor::make('description')
                    ->label('Mô tả')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã SP')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá SP (PHP)')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->enum(ProductStatus::all()),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->defaultImageUrl('/images/placeholder.png'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
