<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Customer;
use App\Models\Task;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
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
use Illuminate\Support\Str;

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
                                ->label(__("task.content"))
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
                                ->label(__("task.image"))
                                ->helperText(__("task.image_hint"))
                                ->image()
                                ->disk("tasks")
                                ->maxSize(1024)
                                ->imageResizeMode("cover")
                                ->imageCropAspectRatio("4:3")
                                ->imageResizeTargetWidth("640")
                                ->imageResizeTargetHeight("480")
                                ->getUploadedFileNameForStorageUsing(
                                    fn($file) => Str::uuid()
                                ),
                        ]),
                        Section::make("Crontab")
                            ->description(__("task.cron_desc"))
                            ->schema([
                                TextInput::make("cron")
                                    ->label(__("task.cron"))
                                    ->placeholder("* * * * *")
                                    ->helperText(__("task.cron_help"))
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(["lg" => 2]),
                Group::make()
                    ->schema([
                        Section::make("Group & Channel")->schema([
                            Select::make("chat_id")
                                ->label("Group & Channel")
                                ->options(
                                    Customer::group()->pluck("name", "id")
                                )
                                ->searchable()
                                ->required(),
                        ]),
                        Section::make(__("task.status"))->schema([
                            Toggle::make("enabled")
                                ->label(__("task.enabled"))
                                ->default(true)
                                ->inline(),
                        ]),
                        Section::make("TimeLine")
                            ->schema([
                                Placeholder::make("created_at")
                                    ->label(__("task.created_at"))
                                    ->content(
                                        fn(
                                            Task $record
                                        ): ?string => $record->created_at?->diffForHumans()
                                    ),

                                Placeholder::make("updated_at")
                                    ->label(__("task.updated_at"))
                                    ->content(
                                        fn(
                                            Task $record
                                        ): ?string => $record->updated_at?->diffForHumans()
                                    ),
                            ])
                            ->columnSpan(["lg" => 1])
                            ->hidden(fn(?Task $record) => $record === null),
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
                TextColumn::make("name")
                    ->label(__("task.name"))
                    ->weight("medium"),
                TextColumn::make("chat.name")
                    ->label(__("task.chat"))
                    ->color("green"),
                TextColumn::make("content")
                    ->label(__("task.content"))
                    ->html(),
                TextColumn::make("cron")->label(__("task.cron")),
                ToggleColumn::make("enabled")->label(__("task.enabled")),
                ImageColumn::make("image")
                    ->disk("tasks")
                    ->defaultImageUrl("/storage/default.jpg")
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

    public static function getLabel(): ?string
    {
        return __("task.label");
    }
}
