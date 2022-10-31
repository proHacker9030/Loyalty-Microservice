<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\OrderAttempts;
use App\Models\Orders;

class OrderAttemptsRepository
{
    public function createOrUpdate(Orders $order, int $statusTo): void
    {
        $attempt = OrderAttempts::firstOrNew(['orders_id' => $order->id]);
        $attempt->status_id_from = $order->status_id;
        $attempt->status_id_to = $statusTo;
        $attempt->orders_id = $order->id;
        if (is_null($attempt->attempts_count)) {
            $attempt->attempts_count = 0;
        } else {
            ++$attempt->attempts_count;
        }
        $attempt->save();
    }

    public function getAttemptsQuantity(int $orderId): int
    {
        /** @var OrderAttempts $attempt */
        $attempt = OrderAttempts::where(['orders_id' => $orderId])->first();
        if (is_null($attempt)) {
            return 0;
        }

        return $attempt->attempts_count;
    }
}
