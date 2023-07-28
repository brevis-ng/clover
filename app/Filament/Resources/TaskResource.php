<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Customer;
use App\Models\Task;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationGroup = "System";
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = "heroicon-o-calendar";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make()->schema([
                            TextInput::make("name")->required(),
                            RichEditor::make("content")
                                ->label(__("settings.content"))
                                ->hint(
                                    "Using bold, italic, strike and underline styles only"
                                )
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([
                                    "bold",
                                    "italic",
                                    "strike",
                                    "underline",
                                ])
                                ->required()
                                ->columnSpanFull(),
                            FileUpload::make("image")
                                ->label(__("settings.image"))
                                ->helperText(__("product.image_hint"))
                                ->image()
                                ->disk("tasks")
                                ->maxSize(1024)
                                ->imageResizeMode("cover")
                                ->imageCropAspectRatio("4:3")
                                ->imageResizeTargetWidth("640")
                                ->imageResizeTargetHeight("480")
                                ->preserveFilenames(),
                        ]),
                        Section::make("Crontab")
                            ->description(__("settings.cron_desc"))
                            ->schema([
                                TextInput::make("cron")
                                    ->label(__("settings.crontab"))
                                    ->placeholder("* * * * *")
                                    ->helperText(__("settings.cron_help"))
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(["lg" => 2]),
                Group::make()
                    ->schema([
                        Section::make("Group & Channel")->schema([
                            Select::make("chat_id")
                                ->label("Group & Channel")
                                ->options(Customer::group()->pluck("name", "id"))
                                ->searchable()
                                ->required(),
                        ]),
                        Section::make("Trạng thái")->schema([
                            Toggle::make("enabled")
                                ->label(__("settings.enabled"))
                                ->default(true)
                                ->inline(),
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
                TextColumn::make("id"),
                TextColumn::make("name")->weight("medium"),
                TextColumn::make("chat.name")
                    ->label("Group")
                    ->color("green"),
                TextColumn::make("content")->html(),
                TextColumn::make("cron")->label("Schedule"),
                ToggleColumn::make("enabled")->label(__("settings.enabled")),
                ImageColumn::make("image")
                    ->disk("tasks")
                    ->defaultImageUrl("/images/placeholder.png")
                    ->square(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            "index" => Pages\ListTasks::route("/"),
            "create" => Pages\CreateTask::route("/create"),
            "edit" => Pages\EditTask::route("/{record}/edit"),
        ];
    }
}
