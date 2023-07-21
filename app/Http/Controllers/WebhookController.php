<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class WebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Nutgram $bot)
    {
        Log::info("Telegram Bot: " . $bot->message());
        $bot->run();
    }
}
