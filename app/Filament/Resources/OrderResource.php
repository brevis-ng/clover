<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = "Shop";
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = "heroicon-o-shopping-bag";

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),
                        // Forms\Components\Section::make("Order items")
                        //     ->schema(static::getFormSchema("items")),
                    ])
                    ->columnSpan([
                        "lg" => fn(?Order $record) => $record === null ? 3 : 2,
                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->label("Created at")
                            ->content(
                                fn (Order $record ): ?string => $record->created_at?->diffForHumans()
                            ),

                        Forms\Components\Placeholder::make("updated_at")
                            ->label("Last modified at")
                            ->content(
                                fn (Order $record): ?string => $record->updated_at?->diffForHumans()
                            ),
                    ])
                    ->columnSpan(["lg" => 1])
                    ->hidden(fn(?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("customer.name")
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make("status")
                    ->label(trans("admin.status"))
                    ->enum(OrderStatus::all())
                    ->colors([
                        "danger" => OrderStatus::CANCELLED->value,
                        "warning" => OrderStatus::PENDING->value,
                        "success" => fn($state) => in_array($state, [
                            OrderStatus::COMPLETED->value,
                            OrderStatus::SHIPPED->value,
                        ]),
                    ]),
                Tables\Columns\TextColumn::make("subtotal")
                    ->label(__("admin.subtotal"))
                    ->sortable(),
                Tables\Columns\TextColumn::make("shipping_fee")
                    ->label(trans("admin.shipping_fee"))
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(trans("admin.created_at"))
                    ->date()
                    ->toggleable(),
            ])
            ->filters([Tables\Filters\TrashedFilter::make()])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListOrders::route("/"),
            "create" => Pages\CreateOrder::route("/create"),
            "edit" => Pages\EditOrder::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === 'items') {
            return [
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('items')
                            ->label('Product')
                            ->options(Product::query()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ]),

                        Forms\Components\TextInput::make('qty')
                            ->numeric()
                            ->default(1)
                            ->columnSpan([
                                'md' => 2,
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->disabled()
                            ->numeric()
                            ->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->orderable()
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns([
                        'md' => 10,
                    ])
                    ->required(),
            ];
        }

        return [
            Forms\Components\TextInput::make('id')
                ->disabled()
                ->required(),
            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->required(),
                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Create customer')
                        ->modalButton('Create customer')
                        ->modalWidth('lg');
                }),

            Forms\Components\Select::make('status')
                ->options(OrderStatus::all())
                ->default(OrderStatus::PENDING->value)
                ->required(),

            // Forms\Components\MarkdownEditor::make('description')
            //     ->columnSpan('full'),
        ];
    }
}
