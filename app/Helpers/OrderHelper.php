<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enum\OrderStatuses;

class OrderHelper
{
    public static function isNeedToSetFiscalCheck(int $status_id)
    {
        if (OrderStatuses::PREPARED_FOR_PAY === $status_id) {
            return false;
        }

        return OrderStatuses::CALCULATED === $status_id || OrderStatuses::PREPARED_FOR_PAY_LENTA === $status_id;
    }

    public static function isNeedToConfirm(int $status_id)
    {
        if (OrderStatuses::CONFIRMED === $status_id) {
            return false;
        }

        return OrderStatuses::PREPARED_FOR_PAY === $status_id || OrderStatuses::CONFIRMED_LOYALTY === $status_id;
    }
}
