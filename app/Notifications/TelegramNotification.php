<?php

declare(strict_types=1);

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Spatie\Health\Checks\Result;
use Spatie\Health\Notifications\CheckFailedNotification;

class TelegramNotification extends CheckFailedNotification
{
    public function toTelegram(): TelegramMessage
    {
        $messages = array_map(function (Result $result): string {
            return $result->getNotificationMessage();
        }, $this->results);
        $content = sprintf("*%s* (%s) \n\n - ", config('app.name'), config('app.url'));
        $content .= implode("\n\n - ", $messages);

        return TelegramMessage::create()
            ->to(config('health.notifications.telegram.chat_id'))
            ->content($content);
    }
}
