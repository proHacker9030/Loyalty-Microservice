<?php

declare(strict_types=1);

namespace App\Services\Systems\CityCard;

use App\Services\Contracts\LoyaltyRulesInterface;

class CityCardRules implements LoyaltyRulesInterface
{
    public function isNeedRetryNullLoyalty(int $cartsQuantity): bool
    {
        if ($cartsQuantity > 0) {
            return true;
        }

        return false;
    }

    public function isNeedCancelLoyalty(float $bonuses, int|string|null $loyaltySystemOperationId): bool
    {
        if ($bonuses > 0) {
            return true;
        }

        return false;
    }
}
