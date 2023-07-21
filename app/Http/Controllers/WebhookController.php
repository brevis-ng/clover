<?php

namespace App\Http\Controllers;

use SergiX44\Nutgram\Nutgram;

class WebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Nutgram $bot)
    {
        $bot->run();
    }
}
