<?php

namespace App\Filament\Pages;

use App\Settings\TelegramBotSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Command\MenuButtonWebApp;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class ManageTelegramBot extends SettingsPage
{
    protected static ?string $navigationGroup = "Settings";
    protected static ?int $navigationSort = 2;
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
                    Toggle::make("should_send_start_msg")->label(
                        __("settings.should_send_start_msg")
                    ),
                    FileUpload::make("start_msg_photo")
                        ->label(__("settings.start_msg_photo"))
                        ->image()
                        ->preserveFilenames()
                        ->maxSize(1024),
                    MarkdownEditor::make("start_msg_content")
                        ->label(__("settings.start_msg_content"))
                        ->requiredIf("should_send_start_msg", "true")
                        ->maxLength(1024)
                        ->disableToolbarButtons([
                            "bulletList",
                            "orderedList",
                            "link",
                            "attachFiles",
                        ]),
                ])
                ->description(__("settings.start_message_description"))
                ->columns(2),
            Section::make("Notifications")
                ->schema([
                    TagsInput::make("administrators")
                        ->label(__("settings.administrators"))
                        ->helperText(__("settings.administrators_hint"))
                        ->required(),
                    TagsInput::make("customers_support")
                        ->label(__("settings.customers_support"))
                        ->helperText(__("settings.customers_support_hint"))
                        ->required(),
                ])
                ->columns(2),
        ];
    }

    protected function afterSave()
    {
        Telegram::setChatMenuButton(
            menu_button: new MenuButtonWebApp(
                text: app(TelegramBotSettings::class)->webapp_menu_button,
                web_app: new WebAppInfo(app(TelegramBotSettings::class)->webapp_url)
            )
        );
    }
}