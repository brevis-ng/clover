<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationGroup = "Settings";
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = "heroicon-o-cog";

    protected static string $settings = GeneralSettings::class;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make("bot_token")
                ->label("Telegram Bot Token")
                ->required(),
            TextInput::make("shop_telegram_id")
                ->label("Shop Telegram ID")
                ->numeric()
                ->helperText("Các thông báo về đơn hàng, cập nhật, .v.v sẽ được gửi tới Telegram này. Lấy ID bằng cách chat với bot @cloverbot"),
        ];
    }
}
