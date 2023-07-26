<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = "System";
    protected static ?int $navigationSort = 3;
    protected static ?string $label = "Administrator";
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = "heroicon-o-user";

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make("name")->required(),
            TextInput::make("email")
                ->required()
                ->email()
                ->unique(Author::class, "email", ignoreRecord: true),
            TextInput::make("username")->label("Telegram username"),
            TextInput::make("telegram_id")->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make("name")
                            ->searchable()
                            ->sortable()
                            ->weight("medium")
                            ->alignLeft(),
                        TextColumn::make("email")
                            ->searchable()
                            ->sortable()
                            ->color("secondary")
                            ->alignLeft(),
                    ]),
                    TextColumn::make("username"),
                    TextColumn::make("telegram_id")
                        ->icon("icons.telegram")
                        ->iconPosition("before"),
                    TextColumn::make("created_at")
                        ->date()
                        ->sortable(),
                ]),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
        ];
    }
}
