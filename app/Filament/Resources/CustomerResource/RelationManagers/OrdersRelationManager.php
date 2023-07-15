<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\OrderStatus;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = "orders";
    protected static ?string $recordTitleAttribute = "id";
    protected static ?string $inverseRelationship = "customer";

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id"),
                BadgeColumn::make("status")
                    ->label(__("order.status"))
                    ->enum(OrderStatus::all())
                    ->colors([
                        "danger" => OrderStatus::CANCELLED->value,
                        "warning" => OrderStatus::PENDING->value,
                        "success" => fn($state) => in_array($state, [
                            OrderStatus::COMPLETED->value,
                            OrderStatus::SHIPPED->value,
                        ]),
                    ]),
                TextColumn::make("address")->label(__("order.address")),
                TextColumn::make("total_amount")->label(
                    __("order.total_amount")
                ),
                TextColumn::make("shipping_amount")->label(
                    __("order.shipping_amount")
                ),
                TextColumn::make("payment_method")
                    ->label(__("order.payment_method"))
                    ->enum([
                        "cod" => __("order.cod"),
                        "bank" => __("order.bank"),
                    ]),
            ])
            ->filters([])
            ->headerActions([Tables\Actions\AssociateAction::make()])
            ->actions([Tables\Actions\DissociateAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
