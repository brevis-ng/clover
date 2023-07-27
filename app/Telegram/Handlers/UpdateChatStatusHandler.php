<?php

namespace App\Telegram\Handlers;

use App\Models\Customer;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ChatMemberStatus;

class UpdateChatStatusHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $chat = $bot->getData(Customer::class);
        $chatMember = $bot->chatMember();

        if ($chat !== null && $chatMember !== null) {
            $chat->blocked_at = in_array(
                $chatMember->new_chat_member?->status,
                [ChatMemberStatus::KICKED, ChatMemberStatus::LEFT]
            )
                ? now()
                : null;

            $chat->save();
        }
    }
}
