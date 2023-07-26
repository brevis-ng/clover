<?php

use App\Telegram\Commands\CancelCommand;
use App\Telegram\Commands\ClearCacheCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\OrderConversation;
use App\Telegram\Conversations\OrderManageConversation;
use App\Telegram\Middleware\HasOrder;
use App\Telegram\Middleware\IsAdmin;
use App\Telegram\Middleware\VerifyOrder;
use Nutgram\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

Telegram::onCommand("start", StartCommand::class);
Telegram::onCommand("myorder", OrderConversation::class)->middleware(HasOrder::class);
Telegram::onText(__("order.check"), OrderConversation::class)->middleware(HasOrder::class);

Telegram::group(function () {
    Telegram::onCommand("order {order_number}", OrderManageConversation::class)->middleware(VerifyOrder::class);
    Telegram::onCommand("clear_cache", ClearCacheCommand::class);
})->middleware(IsAdmin::class);

Telegram::onCommand("help", HelpCommand::class);
Telegram::onCommand("cancel", CancelCommand::class);
