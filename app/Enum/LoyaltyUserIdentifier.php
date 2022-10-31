<?php

declare(strict_types=1);

namespace App\Enum;

enum LoyaltyUserIdentifier: string
{
    case CARD = 'card';
    case PHONE = 'phone';
    case UUID = 'uuid';
}
