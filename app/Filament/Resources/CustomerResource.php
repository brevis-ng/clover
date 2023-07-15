<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Models\Customer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationGroup = "Shop";
    protected static ?string $recordTitleAttribute = "name";
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = "heroicon-o-user-group";

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make("name")
                            ->label(__("customer.name"))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make("phone")
                            ->label(__("customer.phone"))
                            ->maxLength(12)
                            ->tel(),
                        TextInput::make("telegram_id")
                            ->label(__("customer.telegram_id"))
                            ->numeric(),
                        TextInput::make("telegram_username")
                            ->label(__("customer.telegram_username"))
                            ->minLength(5),
                    ])
                    ->columns(2)
                    ->columnSpan([
                        "lg" => fn(?Customer $record) => $record === null
                            ? 3
                            : 2,
                    ]),
                Card::make()
                    ->schema([
                        Placeholder::make("created_at")
                            ->label(__("customer.created_at"))
                            ->content(
                                fn(
                                    Customer $record
                                ): ?string => $record->created_at?->diffForHumans()
                            ),

                        Placeholder::make("updated_at")
                            ->label(__("customer.updated_at"))
                            ->content(
                                fn(
                                    Customer $record
                                ): ?string => $record->updated_at?->diffForHumans()
                            ),
                    ])
                    ->columnSpan(1)
                    ->hidden(fn(?Customer $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label(__("customer.name"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("phone")
                    ->label(__("customer.phone"))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make("telegram_id")
                    ->label(__("customer.telegram_id"))
                    ->formatStateUsing(
                        fn(string $state): string => "tg://user?id={$state}"
                    )
                    ->placeholder("N/A")
                    ->copyable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("customer.created_at"))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([Tables\Filters\TrashedFilter::make()])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [OrdersRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListCustomers::route("/"),
            "edit" => Pages\EditCustomer::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
