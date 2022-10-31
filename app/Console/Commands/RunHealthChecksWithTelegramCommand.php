<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Notifications\TelegramNotification;
use Illuminate\Support\Collection;
use Spatie\Health\Checks\Result;
use Spatie\Health\Commands\RunHealthChecksCommand;

class RunHealthChecksWithTelegramCommand extends RunHealthChecksCommand
{
    protected function sendNotification(Collection $results): RunHealthChecksCommand
    {
        $resultsWithMessages = $results->filter(fn (Result $result) => !empty($result->getNotificationMessage()));

        if (0 === $resultsWithMessages->count()) {
            return $this;
        }

        $notifiableClass = config('health.notifications.notifiable');

        /** @var \Spatie\Health\Notifications\Notifiable $notifiable */
        $notifiable = app($notifiableClass);

        /** @var array<int, Result> $results */
        $results = $resultsWithMessages->toArray();

        $notification = (new TelegramNotification($results));

        $notifiable->notify($notification);

        return $this;
    }
}
