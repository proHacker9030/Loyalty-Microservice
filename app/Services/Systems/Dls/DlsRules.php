<?php

declare(strict_types=1);

namespace App\Services\Systems\Dls;

use App\Services\Contracts\LoyaltyRulesInterface;

class DlsRules implements LoyaltyRulesInterface
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
        return !is_null($loyaltySystemOperationId);
    }
}
