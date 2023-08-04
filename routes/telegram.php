<?php

use App\Telegram\Commands\CancelCommand;
use App\Telegram\Commands\ClearCacheCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\OrderConversation;
use App\Telegram\Conversations\OrderManageConversation;
use App\Telegram\Handlers\UpdateChatStatusHandler;
use App\Telegram\Middleware\CollectChat;
use App\Telegram\Middleware\HasOrder;
use App\Telegram\Middleware\IsAdmin;
use App\Telegram\Middleware\VerifyOrder;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

/*
|--------------------------------------------------------------------------
| Global middlewares
|--------------------------------------------------------------------------
*/
Telegram::middleware(CollectChat::class);

/*
|--------------------------------------------------------------------------
| Bot handlers
|--------------------------------------------------------------------------
*/
Telegram::onMyChatMember(UpdateChatStatusHandler::class);

/*
|--------------------------------------------------------------------------
| Bot commands
|--------------------------------------------------------------------------
*/
Telegram::onCommand("start", StartCommand::class);
Telegram::onCommand("help", HelpCommand::class);
Telegram::onCommand("cancel", CancelCommand::class);
Telegram::onCommand("myorder", OrderConversation::class)->middleware(HasOrder::class);
Telegram::onText(__("order.check"), OrderConversation::class)->middleware(HasOrder::class);

/*
|--------------------------------------------------------------------------
| Bot admin commands
|--------------------------------------------------------------------------
*/
Telegram::group(function () {
    Telegram::onCommand("order", function () {
        Telegram::sendMessage(
            "❌ *Invalid Format\!*\nChecking order should be entered in the following format:\n `/order ABC123`",
            parse_mode: ParseMode::MARKDOWN
        );
    });
    Telegram::onCommand("order {order_number}", OrderManageConversation::class)->middleware(VerifyOrder::class);
    Telegram::onCommand("cache", ClearCacheCommand::class);
})->middleware(IsAdmin::class);

