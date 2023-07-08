<?php

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

use Nutgram\Laravel\Facades\Telegram;

Telegram::onCommand("start", function () {
    return Telegram::sendMessage("Hello, world!");
});

Telegram::onCommand("stop", function () {
    return Telegram::sendMessage("Bye!");
});
