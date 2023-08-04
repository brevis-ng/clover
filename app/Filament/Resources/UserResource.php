<?php

namespace App\Filament\Resources;

use App\Enums\Roles;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Customer;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = "System";
    protected static ?int $navigationSort = 3;
    protected static ?string $label = "Administrator";
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = "heroicon-o-user";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                TextInput::make("name")->required(),
                                TextInput::make("email")
                                    ->required()
                                    ->email()
                                    ->unique(User::class, "email", ignoreRecord: true),
                                TextInput::make("password")
                                    ->password()
                                    ->minLength(6)
                                    ->same('passwordConfirmation')
                                    ->hidden(fn(?User $record) => $record === null)
                                    ->required(),
                                TextInput::make("passwordConfirmation")
                                    ->password()
                                    ->minLength(6)
                                    ->hidden(fn(?User $record) => $record === null)
                                    ->required(),
                            ])
                            ->columns(2),
                        Section::make("Telegram")->schema([
                            Select::make("telegram_id")
                                ->options(Customer::all()->pluck("name", "id"))
                                ->searchable()
                                ->required(),
                        ]),
                    ])
                    ->columnSpan(["lg" => 2]),
                Group::make()
                    ->schema([
                        Section::make(__("customer.role"))->schema([
                            Select::make("role")
                                ->label(__("customer.role"))
                                ->options(Roles::all())
                                ->default(Roles::ASSISTANT->value)
                                ->required(),
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
                TextColumn::make("name")
                    ->searchable()
                    ->sortable()
                    ->weight("medium"),
                TextColumn::make("email")
                    ->searchable()
                    ->color("secondary"),
                TextColumn::make("telegram_id")
                    ->icon("heroicon-o-link")
                    ->url(fn($record) => $record->getTelegramUrl(), true),
                BadgeColumn::make("role")
                    ->colors([
                        "danger" => "Administrator",
                        "success" => "Assistant",
                    ])
                    ->sortable(),
                TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make(__("customer.edit_password"))
                        ->action(function ($record, $data) {
                            $record->password = $data["password"];
                            $record->save();
                        })
                        ->form([
                            TextInput::make("password")->required(),
                            TextInput::make("confirmation")->same("password"),
                        ])
                        ->icon("heroicon-o-lock-closed"),
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
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
        ];
    }
}
