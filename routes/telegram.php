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


Telegram::onCommand('start', function () {
    Telegram::sendMessage('Hello, world!');
})->description('The start command!');
