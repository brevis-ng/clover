<?php

use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversations\OrderConversation;
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
Telegram::onCommand("order", OrderConversation::class);
