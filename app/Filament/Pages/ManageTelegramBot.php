<?php

namespace App\Filament\Pages;

use App\Settings\TelegramBotSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommand;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeDefault;
use SergiX44\Nutgram\Telegram\Types\Command\MenuButtonCommands;
use SergiX44\Nutgram\Telegram\Types\Command\MenuButtonWebApp;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class ManageTelegramBot extends SettingsPage
{
    protected static ?string $navigationGroup = "System";
    protected static ?string $title = "Telegram Bot";
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = "heroicon-o-cog";

    protected static string $settings = TelegramBotSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Section::make("WebApp Bot")
                ->schema([
                    TextInput::make("bot_username")
                        ->label(__("settings.bot_username"))
                        ->required(),
                    TextInput::make("bot_token")
                        ->label(__("settings.bot_token"))
                        ->required(),
                    TextInput::make("webapp_inline_button")
                        ->label(__("settings.webapp_inline_button"))
                        ->hint(__("settings.inline_btn_hint"))
                        ->required(),
                    TextInput::make("webapp_menu_button")
                        ->label(__("settings.webapp_menu_button"))
                        ->required(),
                    TextInput::make("webapp_url")
                        ->label(__("settings.webapp_url"))
                        ->hint(__("settings.webapp_url_hint"))
                        ->required()
                        ->url()
                        ->startsWith("https://"),
                ])
                ->columns(2),
            Section::make("Greating messages")
                ->schema([
                    RichEditor::make("start_msg_content")
                        ->label(__("settings.start_msg_content"))
                        ->maxLength(1024)
                        ->disableAllToolbarButtons()
                        ->enableToolbarButtons([
                            "bold",
                            "italic",
                            "strike",
                            "underline",
                        ])
                        ->hint("Using bold, italic, strike and underline styles only"),
                    FileUpload::make("start_msg_photo")
                        ->label(__("settings.start_msg_photo"))
                        ->image()
                        ->preserveFilenames()
                        ->maxSize(1024),
                ])
                ->description(__("settings.start_message_description"))
                ->columns(2),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $html_el = [
            "<p>",
            "</p>",
            "<span style=\"text-decoration: underline;\">",
            "</span>",
        ];
        $replacement = ["", "\n", "<u>", "</u>"];
        $content = str_replace(
            $html_el,
            $replacement,
            $data["start_msg_content"]
        );

        $data["start_msg_content"] = $content;

        return $data;
    }

    protected function afterSave()
    {
        Telegram::setChatMenuButton(
            menu_button: new MenuButtonWebApp(
                text: app(TelegramBotSettings::class)->webapp_menu_button,
                web_app: new WebAppInfo(
                    app(TelegramBotSettings::class)->webapp_url
                )
            )
        );

        Telegram::setMyCommands(
            [
                BotCommand::make("start", "Welcome message"),
                BotCommand::make("myorder", "Tracking your order"),
                BotCommand::make(
                    "cancel",
                    "Close a conversation or a keyboard"
                ),
            ],
            new BotCommandScopeDefault()
        );
    }
}
