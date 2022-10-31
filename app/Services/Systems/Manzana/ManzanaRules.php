<?php

declare(strict_types=1);

namespace App\Services\Systems\Manzana;

use App\Services\Contracts\LoyaltyRulesInterface;

class ManzanaRules implements LoyaltyRulesInterface
{
    public function isNeedRetryNullLoyalty(int $cartsQuantity): bool
    {
        return 0 !== $cartsQuantity;
    }

    public function isNeedCancelLoyalty(float $bonuses, int|string|null $loyaltySystemOperationId): bool
    {
        return !is_null($loyaltySystemOperationId);
    }
}
