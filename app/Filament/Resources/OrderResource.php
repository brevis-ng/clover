<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

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
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),
                        Section::make(__("order.items"))->schema(
                            static::getFormSchema("items")
                        ),
                    ])
                    ->columnSpan([
                        "lg" => fn(?Order $record) => $record === null ? 3 : 2,
                    ]),

                Card::make()
                    ->schema([
                        Placeholder::make("created_at")
                            ->label(__("order.created_at"))
                            ->content(
                                fn(
                                    Order $record
                                ): ?string => $record->created_at?->diffForHumans()
                            ),

                        Placeholder::make("updated_at")
                            ->label(__("order.updated_at"))
                            ->content(
                                fn(
                                    Order $record
                                ): ?string => $record->updated_at?->diffForHumans()
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
                TextColumn::make("order_number")
                    ->label(__("order.order_number"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("customer.name")
                    ->label(__("order.customer"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                BadgeColumn::make("status")
                    ->label(__("order.status"))
                    ->sortable()
                    ->enum(OrderStatus::all())
                    ->colors([
                        "danger" => fn($state) => in_array($state, [
                            OrderStatus::CANCELLED->value,
                            OrderStatus::FAILED->value,
                        ]),
                        "warning" => fn($state) => in_array($state, [
                            OrderStatus::PENDING->value,
                            OrderStatus::PROCESSING->value,
                        ]),
                        "success" => fn($state) => in_array($state, [
                            OrderStatus::COMPLETED->value,
                            OrderStatus::SHIPPED->value,
                        ]),
                    ]),
                TextColumn::make("total_amount")
                    ->label(__("order.total_amount"))
                    ->money(shouldConvert: true)
                    ->sortable(),
                TextColumn::make("shipping_amount")
                    ->label(__("order.shipping_amount"))
                    ->money(shouldConvert: true),
                TextColumn::make("payment_method")
                    ->label(__("order.payment_method"))
                    ->formatStateUsing(
                        fn(string $state): string => Str::upper($state)
                    ),
                TextColumn::make("created_at")
                    ->label(__("order.created_at"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort("updated_at", "desc")
            ->filters([
                SelectFilter::make("status")->options(OrderStatus::class),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make(__("order.chat_with_user"))->url(
                        fn(Order $record): string => $record->customer->getTelegramUrl(),
                        true
                    ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
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

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === "items") {
            return [
                Repeater::make("items")
                    ->relationship()
                    ->schema([
                        Select::make("product_id")
                            ->label(__("product.name"))
                            ->options(Product::query()->pluck("name", "id"))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get, $state) {
                                $set(
                                    "amount",
                                    Product::find($state)?->price *
                                        $get("quantity")
                                );
                            })
                            ->columnSpan(["md" => 5]),

                        TextInput::make("quantity")
                            ->label(__("order.quantity"))
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get, $state) {
                                $set(
                                    "amount",
                                    $state *
                                        Product::find($get("product_id"))
                                            ?->price
                                );
                            })
                            ->columnSpan(["md" => 2]),

                        TextInput::make("amount")
                            ->label(__("order.subtotal"))
                            ->disabled()
                            ->numeric()
                            ->required()
                            ->columnSpan(["md" => 3]),
                    ])
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns(["md" => 10])
                    ->required(),
            ];
        }

        return [
            TextInput::make("order_number")
                ->label(__("order.order_number"))
                ->disabled()
                ->required(),
            Select::make("customer_id")
                ->label(__("order.customer"))
                ->relationship("customer", "name")
                ->searchable()
                ->required(),
            Select::make("status")
                ->label(__("order.status"))
                ->options(OrderStatus::all())
                ->default(OrderStatus::PENDING->value)
                ->required(),
            Select::make("payment_method")
                ->label(__("order.payment_method"))
                ->required()
                ->options([
                    "cod" => __("order.cod"),
                    "bank" => __("order.bank"),
                ]),
            TextInput::make("address")
                ->label(__("order.address"))
                ->required()
                ->columnSpan("full"),
            TextInput::make("shipping_amount")
                ->label(__("order.shipping_amount"))
                ->numeric()
                ->required(),
            TextInput::make("total_amount")
                ->label(__("order.total_amount"))
                ->disabled(),
            TextInput::make("notes")
                ->label(__("order.notes"))
                ->columnSpan("full"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("order.label");
    }
}
